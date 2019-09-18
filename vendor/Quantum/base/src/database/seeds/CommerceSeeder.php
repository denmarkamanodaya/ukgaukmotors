<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

class CommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$gateway = \Quantum\base\Models\PaymentGateway::where('title', 'Paypal Rest API')->first())
        {
            \Quantum\base\Models\PaymentGateway::create( [
                'title' => 'Paypal Rest API',
                'slug' => 'PaypalRest',
                'description' => 'Paypal Rest API',
                'status' => 'active',
                'userTitle' => 'Paypal'
            ] );
        }


    }
}
