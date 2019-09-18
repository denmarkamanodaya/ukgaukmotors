<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PageRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_revisions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('pages_id')->unsigned()->index();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('content');
            $table->text('preContent')->nullable();
            $table->enum('area', ['public', 'members', 'admin'])->default('public');
            $table->enum('status', ['published', 'unpublished'])->default('published');
            $table->string('route');
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
        Schema::drop('page_revisions');
    }
}
