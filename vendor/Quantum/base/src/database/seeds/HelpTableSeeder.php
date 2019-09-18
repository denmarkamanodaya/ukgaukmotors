<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;
use Quantum\base\Models\HelpText;

/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : HelpTableSeeder.php
 **/
class HelpTableSeeder extends Seeder {
    public function run() {

        $help = HelpText::create( [
            'slug' => 'page_content' ,
            'title' => 'Page Content Help' ,
            'content' => '<h6 class="text-semibold">Replacement Fields</h6>
<p>To help personalise the page content to your users the following replacement fields are available for use.</p>
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
</ul>' ,
        ] );

        $help = HelpText::create( [
            'slug' => 'news_content' ,
            'title' => 'News Content Help' ,
            'content' => '<h6 class="text-semibold">Replacement Fields</h6>
<p>To help personalise the news content to your users the following replacement fields are available for use.</p>
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
</ul>' ,
        ] );

        $help = HelpText::create( [
            'slug' => 'emails' ,
            'title' => 'Email Content Help' ,
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
<p>And for the welcome email only</p><ul><li>[password]</li></ul>' ,
        ] );

    }
}