<?php
namespace Quantum\base\database\seeds;

use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //News::truncate();
        // add 1st row
        $news = \Quantum\base\Models\News::create( [
            'title' => 'Welcome To The Admin Area' ,
            'content' => '<p>Hello [firstname]</p>

<p>Feel free to have a good play around here to see what it all does.</p>',
            'area' => 'admin',
            'status' => 'published'
        ] );

        $news->roles()->attach([2]);

        $news = \Quantum\base\Models\News::create( [
            'title' => 'Welcome To The Members Area' ,
            'content' => '<p>Hello [firstname]</p>

<p>The news here can be edited in the admin section.</p>',
            'area' => 'members',
            'status' => 'published'
        ] );

        $news->roles()->attach([3]);

    }
}
