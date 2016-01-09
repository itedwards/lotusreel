<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create posts table
		
		Schema::create('posts', function($table)
		{
			
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->string('description');
			$table->string('file');
			$table->string('file_type');
			$table->integer('user_id')->unsigned();
			$table->integer('collection_id')->unsigned();
			
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('collection_id')->references('id')->on('collections');
			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
