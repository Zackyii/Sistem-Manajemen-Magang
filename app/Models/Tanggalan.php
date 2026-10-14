<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tanggalan extends Model
{
    use HasFactory;

    protected $table = 'tanggalan';

    protected $fillable = [
        'mahasiswa_id',
        'mentor_id',
        'kehadiran_id',
        'tanggal',
        'aktivitas_utama',
        'deskripsi_kegiatan',
        'durasi_jam',
        'status_absen_harian',
        'info_tugas',
        'deadline_tugas',
        'info_libur_event',
        'status_logbook',
        'catatan_mentor',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'deadline_tugas' => 'date',
            'durasi_jam' => 'decimal:1',
        ];
    }

    /** @return BelongsTo<Mahasiswa, $this> */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /** @return BelongsTo<Mentor, $this> */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    /** @return BelongsTo<Kehadiran, $this> */
    public function kehadiran(): BelongsTo
    {
        return $this->belongsTo(Kehadiran::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_logbook) {
            'disetujui' => 'badge-disetujui',
            'ditolak' => 'badge-ditolak',
            'submitted' => 'badge-pending',
            default => 'badge-info',
        };
    }
}
