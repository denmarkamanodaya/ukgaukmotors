<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTenancy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('modules', 'tenant'))
        {
            Schema::table('modules', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('site_categories', 'tenant'))
        {
            Schema::table('site_categories', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('emails', 'tenant'))
        {
            Schema::table('emails', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('import', 'tenant'))
        {
            Schema::table('import', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('menu', 'tenant'))
        {
            Schema::table('menu', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('news', 'tenant'))
        {
            Schema::table('news', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('pages', 'tenant'))
        {
            Schema::table('pages', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('membership_types', 'tenant'))
        {
            Schema::table('membership_types', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('settings', 'tenant'))
        {
            Schema::table('settings', function ($table) {
                $table->string('tenant')->nullable()->index();
            });
        }

        if(!Schema::hasColumn('site_tags', 'tenant'))
        {
            Schema::table('site_tags', function ($table) {
                $table->string('tenant')->nullable()->index();
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
