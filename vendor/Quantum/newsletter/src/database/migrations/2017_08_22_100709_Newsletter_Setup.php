<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewsletterSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->boolean('confirm_non_member')->default(0);
            $table->boolean('visible_in_lists')->default(0);
            $table->boolean('allow_subscribers')->default(0);
            $table->string('news_code')->index();
            $table->string('email_from');
            $table->string('email_from_name');
            $table->timestamps();
        });

        Schema::create('newsletter_roles', function(Blueprint $table) {
            $table->integer('newsletter_id')->unsigned()->index();
            $table->foreign('newsletter_id')->references('id')->on('newsletter')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('newsletter_no_join', function(Blueprint $table) {
            $table->integer('newsletter_id')->unsigned()->index();
            $table->foreign('newsletter_id')->references('id')->on('newsletter')->onDelete('cascade');
            $table->integer('sub_id')->unsigned()->index();
            $table->foreign('sub_id')->references('id')->on('newsletter')->onDelete('cascade');
        });

        Schema::create('newsletter_role_move', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->enum('role_action', ['gain', 'lose'])->default('gain')->nullable();
            $table->integer('role_id')->unsigned();
            $table->integer('newsletter_new_id')->unsigned();
            $table->boolean('start_responder')->default(0);
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->index();
            $table->boolean('email_confirmed')->default(0);
            $table->integer('sequence')->unsigned()->index()->default(0);
            $table->dateTime('sequence_send_on')->nullable()->default(null);
            $table->integer('newsletter_import_id')->unsigned()->index()->nullable()->default(null);
            $table->string('sub_code')->index();
            $table->timestamps();
        });

        Schema::create('newsletter_mail', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->string('message_type')->nullable()->index();
            $table->string('subject');
            $table->text('html_message')->nullable();
            $table->text('plain_message')->nullable();
            $table->integer('position')->unsigned()->index()->default(1);
            $table->integer('interval_amount')->unsigned()->nullable()->default(null);
            $table->enum('interval_type', ['Minutes', 'Hours', 'Days', 'Weeks', 'Months', 'Years'])->default('Days')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_sent_mail', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->string('subject');
            $table->text('html_message');
            $table->text('plain_message');
            $table->integer('sent_count')->unsigned()->default(0);
            $table->integer('opened_count')->unsigned()->default(0);
            $table->dateTime('send_on')->nullable()->default(null);
            $table->dateTime('sent_on')->nullable()->default(null);
            $table->string('mail_code')->index();
            $table->timestamps();
        });

        Schema::create('newsletter_pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->string('page_type')->nullable()->index();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('newsletter_import', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('newsletter_id')->unsigned()->index();
            $table->integer('import_count')->unsigned()->default(0);
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
        Schema::drop('newsletter_import');
        Schema::drop('newsletter_pages');
        Schema::drop('newsletter_sent_mail');
        Schema::drop('newsletter_mail');
        Schema::drop('newsletter_subscribers');
        Schema::drop('newsletter_role_move');
        Schema::drop('newsletter_no_join');
        Schema::drop('newsletter_roles');
        Schema::drop('newsletter');
    }
}
