<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        /*$this->call(ACLTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(HelpTableSeeder::class);
        $this->call(EmailsTableSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(NotificationsSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(CommerceSeeder::class);
        $this->call(Base_Update_1_Seeder::class);
        $this->call(Activity_Install::class);
        $this->call(Notifications_Install::class);
        $this->call(Page_Install::class);
        $this->call(Shortcode_Install::class);
        $this->call(Membership_Install::class);
        $this->call(Commerce_Install::class);*/

        Model::reguard();
    }
}
