<?php

use Illuminate\Database\Migrations\Migration;
use Quantum\base\Models\NotificationEvents;

class NotificationsInstall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('notification_events', 'emails_title'))
        {
            Schema::table('notification_events', function ($table) {
                $table->string('emails_title')->nullable();
            });

            NotificationEvents::where('event', 'auth.login')
                ->update(['emails_title' => 'User Login']);
        }

        //Artisan::call('db:seed', array('--class' => 'Notifications_Install'));
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
