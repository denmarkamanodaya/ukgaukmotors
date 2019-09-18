<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('book', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->string('front_cover');
            $table->string('back_cover');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('book_chapters', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('book_id')->unsigned()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->string('featured_image');
            $table->integer('position');
            $table->timestamps();
            $table->foreign('book_id')->references('id')->on('book')->onDelete('cascade');
        });


        Schema::create('book_page', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_chapters_id')->unsigned()->index();
            $table->integer('book_id')->unsigned()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->text('content');
            $table->integer('position');
            $table->string('featured_image');
            $table->boolean('public_view')->default(0);
            $table->timestamps();
            $table->foreign('book_chapters_id')->references('id')->on('book_chapters')->onDelete('cascade');
        });

        Schema::create('book_meta', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('book_id')->unsigned()->index();
            $table->string('featured_image');
            $table->text('description');
            $table->text('keywords');
            $table->text('type');
            $table->text('robots');
            $table->timestamps();
            $table->foreign('book_id')->references('id')->on('book')->onDelete('cascade');
        });

        Schema::create('book_page_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_page_id')->unsigned()->index();
            $table->integer('book_chapters_id')->unsigned()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->mediumText('content');
            $table->integer('position');
            $table->string('featured_image');
            $table->boolean('public_view')->default(0);
            $table->timestamps();
            $table->foreign('book_page_id')->references('id')->on('book_page')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('book_page_revisions');
        Schema::drop('book_meta');
        Schema::drop('book_page');
        Schema::drop('book_chapters');
        Schema::drop('book');
    }
}
