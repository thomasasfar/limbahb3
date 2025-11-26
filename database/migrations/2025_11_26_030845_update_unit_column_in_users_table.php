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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'unit')) {
                $table->dropColumn('unit');
            }

            $table->unsignedBigInteger('unit_id')->nullable()->after('email');

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);

            $table->dropColumn('unit_id');

            $table->string('unit')->nullable()->after('email');
        });
    }
};
