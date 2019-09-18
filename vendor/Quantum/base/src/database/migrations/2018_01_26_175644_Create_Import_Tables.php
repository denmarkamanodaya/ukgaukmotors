<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('import_area')->nullable();
            $table->string('import_type')->nullable();
            $table->mediumText('content');
            $table->boolean('complete')->default(0);
            $table->timestamps();
        });

        Schema::create('import_categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('import_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('import_categories');
        Schema::drop('import');
    }
}
