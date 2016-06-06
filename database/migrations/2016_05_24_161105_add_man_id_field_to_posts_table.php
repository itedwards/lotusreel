<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManIdFieldToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function(Blueprint $table){

            $table->string('man_id')->unique();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('posts', function(Blueprint $table){

            $table->dropColumn('man_id');
        
        });
    }
}
