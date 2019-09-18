<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBouncedNewsletterSubscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('newsletter_subscribers', 'bounced'))
        {
            Schema::table('newsletter_subscribers', function ($table) {
                $table->integer('bounced')->unsigned()->default(0);
                $table->integer('complaint')->unsigned()->default(0);
                $table->boolean('active')->default(1);
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
        //
    }
}
