<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Shortcodes;

class Shortcodes_Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shortcodes::firstOrCreate([
            'type' => 'User',
            'name' => 'logout',
            'callback' => 'Quantum\base\Shortcodes\User::logout',
            'title' => 'Logout',
            'description' => 'Force a Logout on a user',
        ]);

        \Shortcode::clearCache();
    }
}
