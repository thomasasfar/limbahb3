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
        Schema::create('aktivitas_keluar_limbahs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tgl_keluar')->nullable();
            $table->string('tujuan')->nullable();
            $table->float('jml_keluar')->nullable();
            $table->string('max_penyimpanan')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->longText('keterangan')->nullable();
            $table->string('perlakuan')->nullable();
            $table->unsignedInteger('id_klasifikasilimbah')->nullable();
            $table->foreign('id_klasifikasilimbah')->references('id')->on('klasifikasi_limbahs')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_aktivitaslimbah')->nullable();
            $table->foreign('id_aktivitaslimbah')->references('id')->on('aktivitas_limbahs')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_keluar_limbahs');
    }
};
