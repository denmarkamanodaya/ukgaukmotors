<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->date('start_day')->nullable()->index();
            $table->time('start_time')->nullable()->index();
            $table->date('end_day')->nullable()->index();
            $table->time('end_time')->nullable();
            $table->string('repeat_type')->nullable()->index();
            $table->string('repeat_year')->nullable()->index();
            $table->string('repeat_month')->nullable()->index();
            $table->string('repeat_day')->nullable()->index();
            $table->string('repeat_week')->nullable()->index();
            $table->string('repeat_weekday')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->string('cal_eventable_id')->nullable();
            $table->string('cal_eventable_type')->nullable();
            $table->integer('repeat_amount')->unsigned()->nullable()->index();
            $table->integer('repeated')->unsigned()->nullable()->index();
            $table->date('repeat_finished')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('calendar_events_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_events_id')->unsigned()->index();
            $table->mediumText('description');
            $table->text('address')->nullable();
            $table->string('county')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('event_image')->nullable();
            $table->string('event_url')->nullable();
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
        Schema::drop('calendar_events_meta');
        Schema::drop('calendar_events');
    }
}
