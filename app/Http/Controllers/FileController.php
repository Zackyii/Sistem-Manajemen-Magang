<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Tanggalan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Serve a presensi (kehadiran) file — selfie, bukti, or foto_checkout.
     *
     * @param  string  $type  selfie | bukti | checkout
     */
    public function presensi(Request $request, Kehadiran $kehadiran, string $type = 'selfie'): StreamedResponse|Response
    {
        $this->authorizeKehadiranAccess($kehadiran);

        $path = match ($type) {
            'bukti' => $kehadiran->file_bukti,
            'checkout' => $kehadiran->foto_checkout,
            default => $kehadiran->foto_selfie,
        };

        return $this->streamFile($path, "presensi-{$kehadiran->id}-{$type}");
    }

    /**
     * Serve a logbook (tanggalan) attachment.
     */
    public function logbook(Tanggalan $tanggalan): StreamedResponse|Response
    {
        $this->authorizeTanggalanAccess($tanggalan);

        return $this->streamFile($tanggalan->file_lampiran, "logbook-{$tanggalan->id}");
    }

    /**
     * Stream a file from the private local disk.
     */
    private function streamFile(?string $path, string $fallbackName): StreamedResponse|Response
    {
        if (! $path) {
            return response('File tidak tersedia.', 404);
        }

        // Support legacy files stored in storage/app/public (disk: public)
        // Try private disk first, then fall back to public disk.
        $disk = 'local';
        if (! Storage::disk($disk)->exists($path)) {
            $disk = 'public';
            if (! Storage::disk($disk)->exists($path)) {
                Log::warning('File not found on either disk.', ['path' => $path]);

                return response('File tidak ditemukan.', 404);
            }
        }

        $mime = Storage::disk($disk)->mimeType($path) ?: 'application/octet-stream';
        $size = Storage::disk($disk)->size($path);

        return response()->stream(function () use ($disk, $path) {
            $stream = Storage::disk($disk)->readStream($path);
            if ($stream) {
                fpassthru($stream);
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Authorize access to a kehadiran record's files.
     * - Mahasiswa: only their own records.
     * - Mentor: only records of mahasiswa in their bidang.
     * - Admin: all records.
     */
    private function authorizeKehadiranAccess(Kehadiran $kehadiran): void
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            $mahasiswaId = $user->mahasiswa?->id;
            abort_if($kehadiran->mahasiswa_id !== $mahasiswaId, 403, 'Anda tidak berwenang mengakses file ini.');
        } elseif ($user->role === 'mentor') {
            $mentor = $user->mentor;
            $allowedIds = $mentor?->bidang
                ? $mentor->bidang->mahasiswas()->pluck('id')
                : collect();
            abort_if(! $allowedIds->contains($kehadiran->mahasiswa_id), 403, 'Anda tidak berwenang mengakses file ini.');
        }
        // Admin: allow all
    }

    /**
     * Authorize access to a tanggalan record's files.
     * - Mahasiswa: only their own records.
     * - Mentor: only records of mahasiswa in their bidang.
     * - Admin: all records.
     */
    private function authorizeTanggalanAccess(Tanggalan $tanggalan): void
    {
        $user = auth()->user();

        if ($user->role === 'mahasiswa') {
            $mahasiswaId = $user->mahasiswa?->id;
            abort_if($tanggalan->mahasiswa_id !== $mahasiswaId, 403, 'Anda tidak berwenang mengakses file ini.');
        } elseif ($user->role === 'mentor') {
            $mentor = $user->mentor;
            $allowedIds = $mentor?->bidang
                ? $mentor->bidang->mahasiswas()->pluck('id')
                : collect();
            abort_if(! $allowedIds->contains($tanggalan->mahasiswa_id), 403, 'Anda tidak berwenang mengakses file ini.');
        }
        // Admin: allow all
    }
}
