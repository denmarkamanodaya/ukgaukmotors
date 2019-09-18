<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Quantum\base\Models\User::class, function (Faker $faker) {
    return [
        'username' => $faker->firstName.$faker->lastName,
        'email' => $faker->email,
        'status' => 'active',
        'email_confirmed' => 'true',
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Quantum\base\Models\Profile::class, function (Faker $faker) {
    return [
        'user_id' => '1',
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'address' => $faker->streetAddress,
        'address2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'county' => $faker->state,
        'postcode' => $faker->postcode,
        'country' => $faker->countryCode,
        'telephone' => $faker->phoneNumber,
        'bio' => $faker->sentence(100),
        'picture' => $faker->imageUrl($width = 640, $height = 480),
    ];
});
