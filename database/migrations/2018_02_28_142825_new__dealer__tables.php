<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewDealerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('dealers', 'country'))
        {
            Schema::table('dealers', function ($table) {
                $table->integer('country_id')->unsigned()->nullable()->index();
                $table->dropColumn('country');
            });
            \App\Models\Dealers::whereNull('country_id')->update(['country_id' => 826]);
        }

        Schema::create('dealers_features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->boolean('system')->default(0);
            $table->integer('position')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('dealer_features', function(Blueprint $table) {
            $table->integer('dealers_id')->unsigned()->index();
            $table->foreign('dealers_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->integer('dealers_features_id')->unsigned()->index();
            $table->foreign('dealers_features_id')->references('id')->on('dealers_features')->onDelete('cascade');
        });

        Schema::create('dealer_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug')->index();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->boolean('system')->default(0);
            $table->string('area')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('dealers_categories', function(Blueprint $table) {
            $table->integer('dealers_id')->unsigned()->index();
            $table->foreign('dealers_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->integer('dealer_categories_id')->unsigned()->index();
            $table->foreign('dealer_categories_id')->references('id')->on('dealer_categories')->onDelete('cascade');
        });

        Schema::create('dealer_user', function (Blueprint $table) {
            $table->integer('dealers_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_user');
        Schema::drop('dealers_categories');
        Schema::drop('dealer_categories');
        Schema::drop('dealer_features');
        Schema::drop('dealers_features');
    }
}
