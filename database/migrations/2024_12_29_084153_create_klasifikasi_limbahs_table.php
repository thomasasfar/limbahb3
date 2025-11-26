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
        Schema::create('klasifikasi_limbahs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis_limbah');
            $table->string('kode_limbah');
            $table->string('satuan');
            $table->string('konversi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_limbahs');
    }
};
