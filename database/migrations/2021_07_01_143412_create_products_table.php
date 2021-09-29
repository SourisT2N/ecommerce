<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('name_not_utf8');
            $table->mediumText('description');
            $table->bigInteger('price_old');
            $table->bigInteger('price_new')->nullable();
            $table->string('system');
            $table->string('display');
            $table->string('processor');
            $table->string('graphics');
            $table->string('memory');
            $table->string('hard_drive');
            $table->string('wireless');
            $table->string('battery');
            $table->string('image_display');
            $table->unsignedBigInteger('id_country');
            $table->unsignedBigInteger('id_category');
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_country')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('id_category')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
