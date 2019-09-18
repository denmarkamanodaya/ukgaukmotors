<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogStickyChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('posts', 'sticky'))
        {
            Schema::table('posts', function ($table) {
                $table->boolean('sticky')->default(0)->nullable();
            });
            Schema::table('posts', function ($table) {
                $table->boolean('featured')->default(0)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
