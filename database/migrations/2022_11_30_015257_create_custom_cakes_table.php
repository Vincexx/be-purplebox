<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_cakes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('quantity');
            $table->text('message')->nullable();
            $table->text('remarks')->nullable();
            $table->text('image')->nullable();
            $table->string('status')->nullable();
            $table->text('price')->nullable();
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
        Schema::dropIfExists('custom_cakes');
    }
}
