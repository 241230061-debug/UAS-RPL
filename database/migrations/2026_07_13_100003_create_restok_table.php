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
        Schema::create('restok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buah_id')->nullable()->constrained('buah')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supplier')->nullable();
            $table->unsignedInteger('jumlah');
            $table->unsignedBigInteger('harga_beli');
            $table->unsignedBigInteger('total_biaya');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restok');
    }
};