<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function(Blueprint $table){
           
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_followed')->unsigned();
            $table->integer('user_following')->unsigned();


            $table->foreign('user_followed')->references('id')->on('users');
            $table->foreign('user_following')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('follows');
    }
}
