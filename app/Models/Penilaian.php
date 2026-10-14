<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'mahasiswa_id',
        'mentor_id',
        'nilai_kehadiran',
        'nilai_tugas',
        'nilai_sikap',
        'nilai_akhir',
        'grade',
        'catatan_evaluasi',
        'tanggal_penilaian',
    ];

    protected function casts(): array
    {
        return [
            'nilai_akhir' => 'decimal:2',
            'tanggal_penilaian' => 'datetime',
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

    public function getPredikatAttribute(): string
    {
        return match (true) {
            $this->nilai_akhir >= 90 => 'A (Sangat Baik)',
            $this->nilai_akhir >= 80 => 'B (Baik)',
            $this->nilai_akhir >= 70 => 'C (Cukup)',
            $this->nilai_akhir >= 60 => 'D (Kurang)',
            default => 'E (Tidak Lulus)',
        };
    }
}
