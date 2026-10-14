<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bidang_id',
        'nama',
        'asal_universitas',
        'program_studi',
        'nim',
        'no_telepon',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_verifikasi',
        'dokumen_pendaftaran',
        'catatan_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Bidang, $this> */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /** @return HasMany<Kehadiran, $this> */
    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    /** @return HasMany<Tanggalan, $this> */
    public function tanggalan(): HasMany
    {
        return $this->hasMany(Tanggalan::class);
    }

    /** @return HasOne<Penilaian, $this> */
    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class);
    }

    public function getDurasiMagangAttribute(): int
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 0;
        }

        return (int) $this->tanggal_mulai->diffInWeeks($this->tanggal_selesai);
    }

    public function getSisaWaktuAttribute(): int
    {
        if (! $this->tanggal_selesai) {
            return 0;
        }

        return (int) now()->diffInWeeks($this->tanggal_selesai, false);
    }

    public function getProgressPersenAttribute(): int
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 0;
        }

        $total = $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
        if ($total === 0) {
            return 0;
        }

        $elapsed = $this->tanggal_mulai->diffInDays(now());

        return (int) min(100, round(($elapsed / $total) * 100));
    }
}
