<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipValidateEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('membership_types', 'email_validate'))
        {
            Schema::table('membership_types', function ($table) {
                $table->boolean('email_validate')->default('1')->nullable();
            });
        }

        if(!Schema::hasColumn('membership_types', 'login_after_register'))
        {
            Schema::table('membership_types', function ($table) {
                $table->boolean('login_after_register')->default('1')->nullable();
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
