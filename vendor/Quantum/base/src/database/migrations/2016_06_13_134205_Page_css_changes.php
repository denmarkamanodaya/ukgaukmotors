<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PageCssChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('pages', 'preContent'))
        {
            Schema::table('pages', function ($table) {
                $table->text('preContent')->nullable();
                $table->string('bodyClass')->nullable();
                $table->text('pageCss')->nullable();
                $table->text('pageJs')->nullable();
                $table->boolean('showBreadcrumbs')->default(1)->nullable();
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
        Schema::table('pages', function ($table) {
            $table->dropColumn(['preContent', 'bodyClass', 'pageCss', 'pageJs', 'showBreadcrumbs']);
        });
    }
}
