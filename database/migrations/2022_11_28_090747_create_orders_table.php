<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("product_id")->nullable();
            $table->integer("quantity");
            $table->double("unit_price")->default(0.00);
            $table->double("total_price")->default(0.00);
            $table->string("status");
            $table->text("delivery_address")->nullable();
            $table->text("delivery_date")->nullable();
            $table->text("message")->nullable();
            $table->string("type")->default('normal');
            $table->text("image")->nullable();
            $table->string("remarks")->nullable();
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
        Schema::dropIfExists('orders');
    }
}
