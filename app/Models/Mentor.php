<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bidang_id',
        'nama',
        'nip',
        'no_telepon',
    ];

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

    /** @return HasMany<Tanggalan, $this> */
    public function tanggalan(): HasMany
    {
        return $this->hasMany(Tanggalan::class);
    }

    /** @return HasMany<Penilaian, $this> */
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }

    /** @return HasManyThrough<Mahasiswa, Bidang, $this> */
    public function mahasiswas(): HasManyThrough
    {
        return $this->hasManyThrough(Mahasiswa::class, Bidang::class, 'id', 'bidang_id', 'bidang_id', 'id');
    }
}
