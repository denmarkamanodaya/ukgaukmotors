<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Settings;

class Base_Update_3_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$members_dashboard = Settings::where('name', 'members_deleted_account_page')->tenant()->first())
        {
            Settings::create([
                'name' => 'members_deleted_account_page',
                'data' => 'index'
            ]);
        }
    }
}
