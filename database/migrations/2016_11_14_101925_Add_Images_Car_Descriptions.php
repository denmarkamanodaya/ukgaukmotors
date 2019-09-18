<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagesCarDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_make_description', function ($table) {
            $table->string('featured_image');
        });

        Schema::table('vehicle_model_description', function ($table) {
            $table->string('featured_image');
        });

        Schema::table('vehicle_model_variant_description', function ($table) {
            $table->string('featured_image');
        });
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
