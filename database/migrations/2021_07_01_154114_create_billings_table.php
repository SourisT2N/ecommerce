<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('code_billing')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_payment');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('district');
            $table->string('province');
            $table->string('ward');
            $table->decimal('total');
            $table->boolean('status_payment')->default(false);
            $table->unsignedBigInteger('id_status');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_payment')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('id_status')->references('id')->on('order_status')->onDelete('cascade');
            $table->unique('code_billing','code_billing_id_payment');
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
        Schema::dropIfExists('billings');
    }
}
