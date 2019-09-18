<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalendarCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_categories', function(Blueprint $table) {
            $table->integer('calendar_id')->unsigned()->index();
            $table->foreign('calendar_id')->references('id')->on('calendar_events')->onDelete('cascade');
            $table->integer('categories_id')->unsigned()->index();
            $table->foreign('categories_id')->references('id')->on('site_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar_categories');

    }
}
