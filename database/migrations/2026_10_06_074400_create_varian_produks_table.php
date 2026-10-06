<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varian_produks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produk_id')
                ->constrained('produks')
                ->cascadeOnDelete();

            $table->foreignId('ukuran_id')
                ->constrained('ukurans')
                ->restrictOnDelete();

            $table->foreignId('warna_id')
                ->constrained('warnas')
                ->restrictOnDelete();

            $table->string('sku')->unique();
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->unique([
                'produk_id',
                'ukuran_id',
                'warna_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varian_produks');
    }
};