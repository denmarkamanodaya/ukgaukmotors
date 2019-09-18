<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_model', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('model',128);
        });
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->integer('activity_log_model_id')->unsigned()->nullable();
            $table->foreign('activity_log_model_id')->references('id')->on('activity_log_model')->onDelete('cascade');
            $table->text('text');
            $table->string('ip_address', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('activity_log');
        Schema::drop('activity_log_model');
    }
}
