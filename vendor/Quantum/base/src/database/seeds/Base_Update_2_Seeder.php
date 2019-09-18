<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\Emailing;

class Base_Update_2_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Quantum\base\Models\HelpText::where('title', 'Email Content Help')->update([
            'content' => '<h6 class="text-semibold">Replacement Fields</h6>
<p>To help personalise your email content to your users the following replacement fields are available for use.</p>
<ul>
<li>[username]</li>
<li>[firstname]</li>
<li>[lastname]</li>
<li>[email]</li>
<li>[address]</li>
<li>[address2]</li>
<li>[city]</li>
<li>[county]</li>
<li>[postcode]</li>
<li>[country]</li>
<li>[telephone]</li>
<li>[bio]</li>
</ul>
<p>For the email verification</p><ul>
<li>[verifyEmailUrl]</li>
<li>[verifyEmailText]</li>
</ul>
<p>For the welcome email only</p><ul><li>[password]</li></ul>
<p>For Notification - User Upgraded</p><ul><li>[upgradeMembership]</li></ul>'
        ]);

        if(!$email = Emailing::where('title', 'Email Validation')->tenant()->first())
        {
            Emailing::create( [
                'title' => 'Email Validation' ,
                'system' => '1',
                'subject' => 'Validate Email Address' ,
                'content_html' => 'Hello [firstname]<br>
<br>
To validate your email address please click on the following link<br>
<br>
<a href="[verifyEmailUrl]">[verifyEmailUrl]</a><br>
<br>
Best Wishes' ,
                'content_text' => 'Hello [firstname]

To validate your email address please click on the following link

[verifyEmailUrl]

Best Wishes
' ,
            ] );
        }
        
        
    }
}
