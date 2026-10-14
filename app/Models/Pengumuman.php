<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'admin_id',
        'bidang_id',
        'judul',
        'isi_pengumuman',
        'tanggal_publikasi',
        'tipe',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publikasi' => 'datetime',
        ];
    }

    /** @return BelongsTo<Admin, $this> */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /** @return BelongsTo<Bidang, $this> */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function getIsGlobalAttribute(): bool
    {
        return $this->bidang_id === null;
    }
}
