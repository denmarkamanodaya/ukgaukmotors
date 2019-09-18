<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('data');
            $table->timestamps();
        });

        Schema::create('site_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug')->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->boolean('system')->default(0);
            $table->timestamps();
        });

        Schema::create('site_tags', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::create('site_categories_role', function(Blueprint $table) {
            $table->integer('site_categories_id')->unsigned()->index();
            $table->foreign('site_categories_id')->references('id')->on('site_categories')->onDelete('cascade');
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
        Schema::drop('site_categories_role');
        Schema::drop('site_tags');
        Schema::drop('site_categories');
        Schema::drop('settings');
    }
}
