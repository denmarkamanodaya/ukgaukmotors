<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Categories;
use Quantum\base\Models\Settings;

class Base_Update_4_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$members_dashboard = Settings::where('name', 'use_gravatar')->tenant()->first())
        {
            Settings::create([
                'name' => 'use_gravatar',
                'data' => 'no'
            ]);
        }

        Categories::where('parent_id', 0)->update(['parent_id' => null]);
        Categories::where('user_id', 0)->update(['user_id' => null]);

    }
}
