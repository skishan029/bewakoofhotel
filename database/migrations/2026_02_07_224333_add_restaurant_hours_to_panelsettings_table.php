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
        Schema::table('panelsettings', function (Blueprint $table) {
            $table->time('restaurant_open_time')->nullable()->default('09:00:00');
            $table->time('restaurant_close_time')->nullable()->default('22:00:00');
            $table->boolean('is_restaurant_open')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panelsettings', function (Blueprint $table) {
            $table->dropColumn(['restaurant_open_time', 'restaurant_close_time', 'is_restaurant_open']);
        });
    }
};
