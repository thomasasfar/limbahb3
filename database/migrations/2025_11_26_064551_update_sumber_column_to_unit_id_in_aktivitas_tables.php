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
        // Update aktivitas_limbahs table
        Schema::table('aktivitas_limbahs', function (Blueprint $table) {
            // Drop old string column
            $table->dropColumn('sumber');
        });
        
        Schema::table('aktivitas_limbahs', function (Blueprint $table) {
            // Add new unsignedBigInteger column with foreign key
            $table->unsignedBigInteger('sumber')->nullable()->after('unit');
            $table->foreign('sumber')->references('id')->on('units')->onDelete('set null')->onUpdate('cascade');
        });

        // Update aktivitas_masuk_limbahs table
        Schema::table('aktivitas_masuk_limbahs', function (Blueprint $table) {
            // Drop old string column
            $table->dropColumn('sumber');
        });
        
        Schema::table('aktivitas_masuk_limbahs', function (Blueprint $table) {
            // Add new unsignedBigInteger column with foreign key
            $table->unsignedBigInteger('sumber')->nullable()->after('tgl_masuk');
            $table->foreign('sumber')->references('id')->on('units')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert aktivitas_limbahs table
        Schema::table('aktivitas_limbahs', function (Blueprint $table) {
            $table->dropForeign(['sumber']);
            $table->dropColumn('sumber');
        });
        
        Schema::table('aktivitas_limbahs', function (Blueprint $table) {
            $table->string('sumber')->nullable();
        });

        // Revert aktivitas_masuk_limbahs table
        Schema::table('aktivitas_masuk_limbahs', function (Blueprint $table) {
            $table->dropForeign(['sumber']);
            $table->dropColumn('sumber');
        });
        
        Schema::table('aktivitas_masuk_limbahs', function (Blueprint $table) {
            $table->string('sumber')->nullable();
        });
    }
};
