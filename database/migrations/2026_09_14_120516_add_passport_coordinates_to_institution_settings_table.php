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
        Schema::table('institution_settings', function (Blueprint $table) {
            $table->integer('passport_x')->default(10);
            $table->integer('passport_y')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_settings', function (Blueprint $table) {
            $table->dropColumn(['passport_x', 'passport_y']);
        });
    }
};
