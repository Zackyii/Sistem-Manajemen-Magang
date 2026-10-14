<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesan';

    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'isi_pesan',
        'waktu_kirim',
        'tipe',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kirim' => 'datetime',
            'is_read' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    /** @return BelongsTo<User, $this> */
    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}
