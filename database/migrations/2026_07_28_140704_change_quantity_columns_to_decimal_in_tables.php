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
        Schema::table('buah', function (Blueprint $table) {
            $table->decimal('stok', 10, 2)->default(0.00)->change();
        });

        Schema::table('transaksi_items', function (Blueprint $table) {
            $table->decimal('qty', 10, 2)->change();
        });

        Schema::table('restok', function (Blueprint $table) {
            $table->decimal('jumlah', 10, 2)->change();
        });

        Schema::table('buah_rusak', function (Blueprint $table) {
            $table->decimal('jumlah', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buah', function (Blueprint $table) {
            $table->integer('stok')->default(0)->change();
        });

        Schema::table('transaksi_items', function (Blueprint $table) {
            $table->integer('qty')->change();
        });

        Schema::table('restok', function (Blueprint $table) {
            $table->integer('jumlah')->change();
        });

        Schema::table('buah_rusak', function (Blueprint $table) {
            $table->integer('jumlah')->change();
        });
    }
};
