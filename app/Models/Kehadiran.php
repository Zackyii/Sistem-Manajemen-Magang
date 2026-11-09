<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'mahasiswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'foto_selfie',
        'file_bukti',
        'foto_checkout',
        'koordinat_lat',
        'koordinat_lng',
        'nama_lokasi',
        'koordinat_lat_checkout',
        'koordinat_lng_checkout',
        'nama_lokasi_checkout',
        'status',
        'status_mentor',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'koordinat_lat' => 'decimal:7',
            'koordinat_lng' => 'decimal:7',
            'koordinat_lat_checkout' => 'decimal:7',
            'koordinat_lng_checkout' => 'decimal:7',
        ];
    }

    /** @return BelongsTo<Mahasiswa, $this> */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /** @return HasOne<Tanggalan, $this> */
    public function tanggalan(): HasOne
    {
        return $this->hasOne(Tanggalan::class);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'badge-hadir',
            'terlambat' => 'badge-pending',
            'izin' => 'badge-izin',
            'sakit' => 'badge-izin',
            'alpha' => 'badge-ditolak',
            default => 'badge-info',
        };
    }
}
