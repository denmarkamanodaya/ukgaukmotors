<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersNotificationUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('notification_types', 'allow_members'))
        {
            Schema::table('notification_types', function ($table) {
                $table->boolean('allow_members')->default('1');
            });
            Schema::table('notification_types', function ($table) {
                $table->text('description')->nullable();
            });
        }

        Schema::create('user_notification_settings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('notification_types_id')->unsigned()->index();
            $table->text('setting');
            $table->timestamps();
        });

        Schema::create('user_event_notification', function(Blueprint $table)
        {
            $table->integer('user_notification_settings_id')->unsigned()->index();
            $table->foreign('user_notification_settings_id')->references('id')->on('user_notification_settings')->onDelete('cascade');
            $table->integer('notification_events_id')->unsigned()->index();
            $table->foreign('notification_events_id')->references('id')->on('notification_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_event_notification');
        Schema::drop('user_notification_settings');
    }
}
