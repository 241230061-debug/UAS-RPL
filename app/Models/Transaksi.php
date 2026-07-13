<?php

namespace App\Models;

use App\Models\TransaksiItem;
use App\Models\User;
use Database\Factories\TransaksiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'kode_transaksi',
    'user_id',
    'metode_pembayaran',
    'total_harga',
    'bayar',
    'kembalian',
])]
class Transaksi extends Model
{
    /** @use HasFactory<TransaksiFactory> */
    use HasFactory;

    protected $table = 'transaksi';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
