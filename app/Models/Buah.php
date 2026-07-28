<?php

namespace App\Models;

use Database\Factories\BuahFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kode',
    'nama_buah',
    'kategori',
    'harga',
    'stok',
    'satuan',
    'gambar',
    'keterangan',
    'aktif',
])]
class Buah extends Model
{
    /** @use HasFactory<BuahFactory> */
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'buah';

    /**
     * Atribut yang harus di-cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok' => 'float',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Cek apakah stok buah habis.
     */
    public function stokHabis(): bool
    {
        return $this->stok <= 0;
    }

    /**
     * Cek apakah stok buah menipis (di bawah ambang batas tertentu).
     */
    public function stokMenipis(int $ambangBatas = 5): bool
    {
        return $this->stok > 0 && $this->stok <= $ambangBatas;
    }
}
