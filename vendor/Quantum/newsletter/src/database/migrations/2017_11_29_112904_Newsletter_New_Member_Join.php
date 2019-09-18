<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewsletterNewMemberJoin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('newsletter', 'autojoin_register'))
        {
            Schema::table('newsletter', function ($table) {
                $table->boolean('autojoin_register')->default(0);
                $table->boolean('autojoin_start_responder')->default(0);
                $table->boolean('autojoin_send_welcome_email')->default(0);
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
