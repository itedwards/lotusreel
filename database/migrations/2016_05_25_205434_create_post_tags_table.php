<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tags', function(Blueprint $table){
           
            $table->increments('id');
            $table->timestamps();
            $table->string('post_id');
            $table->string('tag_id');


            $table->foreign('post_id')->references('man_id')->on('posts');
            $table->foreign('tag_id')->references('man_id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post_tags');
    }
}
