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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique()->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_name_english')->nullable();
            $table->string('sku_code')->nullable();
            $table->double('full_price', 20, 2)->nullable();
            $table->double('half_price', 20, 2)->nullable();
            $table->text('featured_photo')->nullable();
            $table->unsignedBigInteger('category_id')->nullable()->comment('table product_categories');
            $table->unsignedBigInteger('sub_category_id')->nullable()->comment('table product_categories');
            $table->text('product_desc')->nullable();
            $table->longText('product_gallery')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_desc')->nullable();
            $table->text('seo_tag')->nullable();
            $table->set('full_lbl_show', ['1', '2'])->default('1')->comment('1=Show 2=None');
            $table->integer('ordering')->nullable();
            $table->text('half_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
