<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookCssChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('book', 'preContent'))
        {
            Schema::table('book', function ($table) {
                $table->text('preContent')->nullable();
                $table->string('bodyClass')->nullable();
                $table->text('pageCss')->nullable();
                $table->text('pageJs')->nullable();
                $table->boolean('showBreadcrumbs')->default(1)->nullable();
                $table->boolean('hideMenu')->default(0)->nullable();
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
