<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // User information table
        
        Schema::create('users' ,function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();

            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('profile_photo');
            $table->string('cover_photo');
            $table->string('followers');
            $table->string('followed');
            $table->string('bio');
            $table->boolean('remember_token');
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        
        Schema::drop('users');
    }
}
