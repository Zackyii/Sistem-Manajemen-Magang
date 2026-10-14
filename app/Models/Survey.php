<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey';

    protected $fillable = [
        'bidang_id',
        'rating_fasilitas',
        'rating_pembimbing',
        'rating_pekerjaan',
        'rating_lingkungan',
        'saran',
        'kesan',
        'is_anonymous',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    /** @return BelongsTo<Bidang, $this> */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }
}
