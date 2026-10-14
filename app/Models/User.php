<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
        'has_agreed_to_rules',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'has_agreed_to_rules' => 'boolean',
        ];
    }

    /** @return HasOne<Admin, $this> */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /** @return HasOne<Mentor, $this> */
    public function mentor(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    /** @return HasOne<Mahasiswa, $this> */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /** @return HasMany<Pesan, $this> */
    public function pesanTerkirim(): HasMany
    {
        return $this->hasMany(Pesan::class, 'pengirim_id');
    }

    /** @return HasMany<Pesan, $this> */
    public function pesanDiterima(): HasMany
    {
        return $this->hasMany(Pesan::class, 'penerima_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
