<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
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

        Schema::create('posts_meta', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('posts_id')->unsigned()->index();
            $table->string('featured_image')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('type');
            $table->text('robots');
            $table->timestamps();
            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('posts_site_tags', function(Blueprint $table) {
            $table->integer('posts_id')->unsigned()->index();
            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
            $table->integer('site_tags_id')->unsigned()->index();
            $table->foreign('site_tags_id')->references('id')->on('site_tags')->onDelete('cascade');
        });

        Schema::create('posts_role', function(Blueprint $table) {
            $table->integer('posts_id')->unsigned()->index();
            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
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
        Schema::drop('posts_role');
        Schema::drop('posts_site_tags');
        Schema::drop('posts_meta');
        Schema::drop('posts');
    }
}
