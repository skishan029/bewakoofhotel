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
        Schema::create('panelsettings', function (Blueprint $table) {
            $table->id();
            $table->string('notification_email')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_one')->nullable();
            $table->string('contact_two')->nullable();
            $table->string('banner_heading')->nullable();
            $table->string('address')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('established_year')->nullable();
            $table->text('about_content')->nullable();
            $table->string('owner_name')->nullable();
            $table->text('owner_about')->nullable();
            $table->text('company_logo')->nullable();
            $table->text('favicon_logo')->nullable();
            $table->text('left_image')->nullable();
            $table->text('right_image')->nullable();
            $table->text('user_image')->nullable();            
            $table->text('user_image2')->nullable();
            $table->text('frequent_image_1')->nullable();            
            $table->text('frequent_image_2')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('pop_back_image')->nullable();
            $table->string('footer_back_image')->nullable();
            $table->string('testimonial_back_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panelsettings');
    }
};
