<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipInstall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('membership_types', 'members_page_after_payment'))
        {
            Schema::table('membership_types', function ($table) {
                $table->string('members_page_after_payment');
                $table->string('guest_page_after_payment');
                $table->dropColumn('page_after_payment');
            });
        }

        if(!Schema::hasColumn('membership_types', 'expires'))
        {
            Schema::table('membership_types', function ($table) {
                $table->boolean('expires')->default(0)->nullable();
            });
        }

        if(!Schema::hasColumn('membership_types', 'summary'))
        {
            Schema::table('membership_types', function ($table) {
                $table->string('summary');
            });
        }

        if(!Schema::hasColumn('membership_types', 'summary'))
        {
            Schema::table('membership_types', function ($table) {
                $table->string('summary');
            });
        }

        //Artisan::call('db:seed', array('--class' => 'Membership_Install'));
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
