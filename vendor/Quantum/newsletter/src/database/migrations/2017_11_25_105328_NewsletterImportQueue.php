<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewsletterImportQueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_import_queue', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->boolean('start_responder')->default(0);
            $table->boolean('send_welcome')->default(0);
            $table->string('csvfile')->index();
            $table->integer('startAt')->unsigned()->default(0)->index();
            $table->boolean('error')->default(0);
            $table->boolean('completed')->default(0);
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
        Schema::drop('newsletter_import_queue');
    }
}
