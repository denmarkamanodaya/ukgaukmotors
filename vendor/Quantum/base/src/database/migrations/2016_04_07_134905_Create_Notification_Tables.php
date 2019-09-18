<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->index(['slug']);
        });

        Schema::create('notification_events', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('event');
            $table->string('title');
            $table->string('description');
            $table->timestamps();

            $table->index(['event']);
        });

        Schema::create('notif_event_notif_type', function(Blueprint $table) {
            $table->integer('notification_events_id')->unsigned()->index();
            $table->foreign('notification_events_id')->references('id')->on('notification_events')->onDelete('cascade');
            $table->integer('notification_types_id')->unsigned()->index();
            $table->foreign('notification_types_id')->references('id')->on('notification_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notif_event_notif_type');
        Schema::drop('notification_events');
        Schema::drop('notification_types');
    }
}
