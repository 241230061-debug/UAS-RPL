<?php

namespace App\Models;

use App\Models\Buah;
use App\Models\Transaksi;
use Database\Factories\TransaksiItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'transaksi_id',
    'buah_id',
    'qty',
    'harga',
    'subtotal',
])]
class TransaksiItem extends Model
{
    /** @use HasFactory<TransaksiItemFactory> */
    use HasFactory;

    protected $table = 'transaksi_items';

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function buah(): BelongsTo
    {
        return $this->belongsTo(Buah::class);
    }
}
