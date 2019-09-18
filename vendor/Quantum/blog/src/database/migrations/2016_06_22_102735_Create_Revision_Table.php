<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posts_id')->unsigned()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->text('content');
            $table->text('summary');
            $table->enum('area', ['public', 'members', 'admin', 'private'])->default('public');
            $table->enum('status', ['published', 'unpublished'])->default('published');
            $table->boolean('publishOnTime')->default(0);
            $table->dateTime('publish_on')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('main_category_id')->unsigned()->index();
            $table->integer('post_category_id')->unsigned()->index();
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
        Schema::drop('post_revisions');
    }
}
