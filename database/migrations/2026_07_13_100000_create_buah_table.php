<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buah', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // kode/SKU produk, mis. BH-0001
            $table->string('nama_buah');
            $table->string('kategori')->nullable(); // Food & Bakery, Drinks, dst (mengikuti kategori di terminal kasir)
            $table->unsignedBigInteger('harga'); // harga jual per satuan (Rupiah)
            $table->unsignedInteger('stok')->default(0);
            $table->string('satuan')->default('kg'); // kg, pcs, ikat, dst
            $table->string('gambar')->nullable(); // path gambar produk
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true); // untuk soft-disable produk tanpa menghapusnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buah');
    }
};
