<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'kuota',
        'deskripsi',
    ];

    /** @return HasMany<Mentor, $this> */
    public function mentors(): HasMany
    {
        return $this->hasMany(Mentor::class);
    }

    /** @return HasMany<Mahasiswa, $this> */
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    /** @return HasMany<Pengumuman, $this> */
    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class);
    }

    public function getTerisiAttribute(): int
    {
        return $this->mahasiswas()->where('status_verifikasi', 'disetujui')->count();
    }

    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->terisi);
    }
}
