<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParsedDealers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parsedDealers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug')->index();
            $table->enum('processing', ['yes', 'no'])->default('no')->nullable();
            $table->enum('error', ['yes', 'no'])->default('no')->nullable();
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
        Schema::drop('parsedDealers');
    }
}
