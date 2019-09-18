<?php
namespace Quantum\base\database\seeds;

use Quantum\base\Models\Profile;
use Illuminate\Database\Seeder;
use Quantum\base\Models\User as User; // to use Eloquent Model

class UserTableSeeder extends Seeder {
    public function run() {
        //User::truncate();
        // add 1st row
        $user = User::create( [
            'email' => 'dave@quantumscripts.co.uk' ,
            'password' => \Hash::make( 'jamie1999' ) ,
            'status' => 'active',
            'email_confirmed' => 'true',
            'username' => 'Quantum',
        ] );

        $user->role()->attach([1,2,3]);

        $profile = factory(\Quantum\base\Models\Profile::class)->create([
            'user_id' => $user->id,
            'picture' => '',
            'first_name' => 'David',
            'last_name' => 'Agar'
        ]);

        /*$user = User::create( [
            'email' => 'davetest@quantumscripts.co.uk' ,
            'password' => Hash::make( 'jamie1999' ) ,
            'status' => 'active',
            'email_confirmed' => 'true',
            'username' => 'DaveTest',
        ] );

        $user->role()->attach(2);

        $profile = factory(\Quantum\base\Models\Profile::class)->create([
            'user_id' => $user->id,
            'picture' => ''
        ]);

        $users = factory(\Quantum\base\Models\User::class, 30)
         ->create()
          ->each(function($u) {
           $u->role()->attach(3);

           $profile = factory(\Quantum\base\Models\Profile::class)->create([
               'user_id' => $u->id,
               'picture' => ''
           ]);

            });*/

    }
}