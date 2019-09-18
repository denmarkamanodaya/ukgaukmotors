<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirewallTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lockouts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('ip_address');
            $table->text('info');
            $table->timestamp('released')->nullable();
            $table->timestamps();
        });

        Schema::create('failures', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('ip_address');
            $table->text('info');
            $table->timestamp('released')->nullable();
            $table->timestamps();
        });

        Schema::create('whitelist', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('ip_address');
            $table->text('info');
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
        Schema::drop('lockouts');
        Schema::drop('failures');
        Schema::drop('whitelist');
    }
}
