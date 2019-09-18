<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('ticket_department_id')->unsigned()->index();
            $table->integer('ticket_status_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('email')->index()->nullable();
            $table->integer('staff_id')->unsigned()->index()->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('tickets_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_activity', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('tickets_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_user', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('position')->nullable();
            $table->text('signature')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('ticketable_id')->unsigned()->index();
            $table->string('ticketable_type')->index();
            $table->string('name');
            $table->string('location');
            $table->enum('type', ['image', 'video', 'file'])->default('image')->index();
            $table->timestamps();
        });

        Schema::create('ticket_department', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug')->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('system')->default(0);
            $table->boolean('default')->default(0);
            $table->string('colour');
            $table->timestamps();
        });

        Schema::create('ticket_department_user', function(Blueprint $table) {
            $table->integer('ticket_department_id')->unsigned()->index();
            $table->foreign('ticket_department_id')->references('id')->on('ticket_department')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('ticket_status', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->string('slug')->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('system')->default(0);
            $table->boolean('default')->default(0);
            $table->string('colour');
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
        Schema::drop('ticket_status');
        Schema::drop('ticket_department_user');
        Schema::drop('ticket_department');
        Schema::drop('ticket_media');
        Schema::drop('ticket_user');
        Schema::drop('ticket_activity');
        Schema::drop('ticket_replies');
        Schema::drop('tickets');
    }
}
