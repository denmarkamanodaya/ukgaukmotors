<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpTextTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helptext', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('title');
            $table->text('content');
            $table->enum('area', ['public', 'members', 'admin'])->default('public');
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
        Schema::drop('helptext');
    }
}
