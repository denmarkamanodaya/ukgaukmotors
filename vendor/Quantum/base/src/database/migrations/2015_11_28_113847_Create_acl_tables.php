<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->integer('position')->unsigned()->default(1);
            $table->boolean('system')->default(0);
            $table->timestamps();
            $table->unique(['name']);
        });

        Schema::create('permission_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_area_id')->unsigned();
            $table->string('name');
            $table->string('title');
            $table->integer('position')->unsigned()->default(1);
            $table->boolean('system')->default(0);
            $table->timestamps();
            $table->unique(['name']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->integer('permission_group_id')->unsigned();
            $table->integer('position')->unsigned()->default(1);
            $table->boolean('system')->default(0);
            $table->timestamps();
            $table->unique(['name']);

            $table->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('cascade');
        });

        Schema::create('permission_user', function(Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->timestamps();
            $table->unique(['name']);
        });

        Schema::create('role_user', function(Blueprint $table) {
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('permission_role', function(Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
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
        Schema::drop('permission_areas');
        Schema::drop('permission_role');
        Schema::drop('role_user');
        Schema::drop('permission_user');
        Schema::drop('permissions');
        Schema::drop('permission_groups');
        Schema::drop('roles');
    }
}
