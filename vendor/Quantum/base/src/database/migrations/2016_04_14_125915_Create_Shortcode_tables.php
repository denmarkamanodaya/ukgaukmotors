<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShortcodeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortcodes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->text('callback');
            $table->text('params')->nullable();
            $table->string('type');
            $table->string('title');
            $table->text('description');
            $table->boolean('system')->default(1);
            $table->boolean('hidden')->default(0);
            $table->enum('area', ['all', 'public', 'members'])->default('all')->nullable();
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
        Schema::drop('shortcodes');
    }
}
