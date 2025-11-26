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
        Schema::create('aktivitas_limbahs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('aktivitas')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('mengetahui')->nullable();
            $table->string('unit')->nullable();
            $table->string('sumber')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('no_form')->nullable();
            $table->integer('revisi')->nullable();
            $table->string('pengumpul')->nullable();
            $table->string('penghasil')->nullable();
            $table->string('menyerahkan')->nullable();
            $table->string('menerima')->nullable();
            $table->string('personil_she')->nullable();
            $table->string('personil_pengamanan')->nullable();
            $table->string('kaunit_she')->nullable();
            $table->string('no_pengumpul')->nullable();
            $table->string('no_penghasil')->nullable();
            $table->string('no_menyerahkan')->nullable();
            $table->string('no_menerima')->nullable();
            $table->string('no_personil_she')->nullable();
            $table->string('no_personil_pengamanan')->nullable();
            $table->string('no_kaunit_she')->nullable();
            $table->longText('signature_pengumpul')->nullable();
            $table->longText('signature_penghasil')->nullable();
            $table->longText('signature_menyerahkan')->nullable();
            $table->longText('signature_menerima')->nullable();
            $table->longText('signature_unit')->nullable();
            $table->longText('signature_keamanan')->nullable();
            $table->longText('signature_she')->nullable();
            $table->timestamp('approved_by_menyerahkan_at')->nullable();
            $table->timestamp('approved_by_menerima_at')->nullable();
            $table->timestamp('approved_by_personil_she_at')->nullable();
            $table->timestamp('approved_by_personil_pengamanan_at')->nullable();
            $table->timestamp('approved_by_kaunit_she_at')->nullable();
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
        Schema::dropIfExists('aktivitas_limbahs');
    }
};
