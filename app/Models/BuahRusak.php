<?php

namespace App\Models;

use Database\Factories\BuahRusakFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'buah_id',
    'user_id',
    'jumlah',
    'catatan',
])]
class BuahRusak extends Model
{
    /** @use HasFactory<BuahRusakFactory> */
    use HasFactory;

    protected $table = 'buah_rusak';

    protected $casts = [
        'jumlah' => 'float',
    ];

    public function buah(): BelongsTo
    {
        return $this->belongsTo(Buah::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}