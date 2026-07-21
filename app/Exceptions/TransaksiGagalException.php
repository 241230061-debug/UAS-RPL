<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Dilempar saat validasi transaksi kasir gagal di dalam DB transaction
 * (stok tidak cukup, produk tidak ditemukan, bayar kurang, dsb).
 * Membawa status HTTP agar controller bisa langsung meneruskannya ke response JSON.
 */
class TransaksiGagalException extends RuntimeException
{
    public function __construct(string $message, public readonly int $status = 422)
    {
        parent::__construct($message);
    }
}
