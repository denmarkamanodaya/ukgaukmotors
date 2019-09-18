<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('role_id')->unsigned();
            $table->text('parent_start')->nullable();
            $table->text('parent_end')->nullable();
            $table->text('child_start')->nullable();
            $table->text('child_end')->nullable();
            $table->boolean('system')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->index();
            $table->enum('type', ['normal', 'dropdown', 'dropdown-header', 'dropdown-submenu'])->default('normal')->nullable();
            $table->integer('parent_id')->unsigned()->nullable()->default(0)->index();
            $table->string('icon');
            $table->string('url');
            $table->string('title');
            $table->string('permission');
            $table->integer('position')->unsigned()->default(1)->index();
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
        Schema::drop('menu');
        Schema::drop('menu_items');
    }
}
