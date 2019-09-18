<?php
namespace Quantum\base\database\seeds;

use Quantum\base\Models\Emailing;
use Illuminate\Database\Seeder;

class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Emailing::create( [
            'title' => 'Welcome Email' ,
            'subject' => 'Welcome [firstname]' ,
            'content_html' => 'Hello [firstname]<br>
<br>
Your login details are as follows<br>
Username: [username]<br>
Password : [password]<br>
<br>
Login url <br>
[loginurl]<br>
<br>
Best Wishes' ,
            'content_text' => 'Hello [firstname]

Your login details are as follows
Username: [username]
Password : [password]

Login url
[loginurl]

Best Wishes
' ,
        ] );
    }
}
