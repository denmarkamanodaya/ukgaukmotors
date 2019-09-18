<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBccSentMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('newsletter_sent_mail', 'personalise'))
        {
            Schema::table('newsletter_sent_mail', function ($table) {
                $table->boolean('personalise')->nullable()->default(1)->index();
                $table->integer('bcc_amount')->nullable()->unsigned();
                $table->string('bcc_to_email')->nullable();
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
