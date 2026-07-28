<?php

namespace App\Models;

use Database\Factories\RestokFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'buah_id',
    'user_id',
    'supplier',
    'jumlah',
    'harga_beli',
    'total_biaya',
    'catatan',
])]
class Restok extends Model
{
    /** @use HasFactory<RestokFactory> */
    use HasFactory;

    protected $table = 'restok';

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
