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
        Schema::create('offer_discounts', function (Blueprint $table) {
            $table->id();
            $table->text('offer_image')->nullable();
            $table->text('back_image')->nullable();
            $table->string('percent')->nullable();
            $table->string('offer_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_discounts');
    }
};
