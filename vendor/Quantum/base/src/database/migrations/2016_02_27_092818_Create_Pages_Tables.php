<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('content');
            $table->enum('area', ['public', 'members', 'admin'])->default('public');
            $table->enum('status', ['published', 'unpublished'])->default('published');
            $table->string('route')->index();
            $table->timestamps();
        });

        Schema::create('page_meta', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index();
            $table->string('featured_image')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('type');
            $table->text('robots');
            $table->timestamps();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });

        Schema::create('page_role', function(Blueprint $table) {
            $table->integer('page_id')->unsigned()->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page_role');
        Schema::drop('page_meta');
        Schema::drop('pages');
    }
}
