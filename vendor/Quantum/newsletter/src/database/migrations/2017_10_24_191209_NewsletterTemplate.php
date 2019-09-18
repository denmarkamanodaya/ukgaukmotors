<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewsletterTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_template', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->text('content');
            $table->timestamps();
        });

        if(!Schema::hasColumn('newsletter', 'newsletter_template_id'))
        {
            Schema::table('newsletter', function ($table) {
                $table->integer('newsletter_templates_id')->default(1)->unsigned()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newsletter_template');
    }
}
