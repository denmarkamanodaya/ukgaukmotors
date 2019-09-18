<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Settings;

class CommerceSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$members_dashboard = Settings::where('name', 'PaypalRest_Hook_Id')->tenant()->first())
        {
            Settings::create([
                'name' => 'PaypalRest_Hook_Id',
                'data' => ''
            ]);
        }
    }
}
