<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('slug');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['free', 'paid'])->default('free')->nullable();
            $table->boolean('subscription')->default(0)->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('subscription_period_amount')->nullable();
            $table->enum('subscription_period_type', ['Days', 'Weeks', 'Months', 'Years'])->default('Months')->nullable();
            $table->boolean('expired_remove_roles')->default(0)->nullable();
            $table->boolean('expired_change_status')->default(0)->nullable();
            $table->enum('expired_change_status_to', ['active', 'inactive'])->default('inactive');
            $table->integer('emails_id')->unsigned();
            $table->string('page_after_registration');
            $table->string('page_after_payment');
            $table->boolean('allow_user_signups')->default(1)->nullable();
            $table->boolean('display_in_collections')->default(1)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->integer('position')->unsigned()->default(1)->nullable();
            $table->boolean('register_default')->default(0)->nullable();
            $table->timestamps();
        });

        Schema::create('membership_t_r', function (Blueprint $table) {
            $table->integer('membership_types_id')->unsigned()->index();
            $table->foreign('membership_types_id')->references('id')->on('membership_types')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        
        Schema::create('membership_t_r_r', function (Blueprint $table) {
            $table->integer('membership_types_id')->unsigned()->index();
            $table->foreign('membership_types_id')->references('id')->on('membership_types')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('membership_t_r_a', function (Blueprint $table) {
            $table->integer('membership_types_id')->unsigned()->index();
            $table->foreign('membership_types_id')->references('id')->on('membership_types')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('user_membership', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('membership_types_id')->unsigned();
            $table->boolean('expires')->default(0);
            $table->dateTime('expires_on')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
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
        Schema::drop('user_membership');
        Schema::drop('membership_t_r_a');
        Schema::drop('membership_t_r_r');
        Schema::drop('membership_t_r');
        Schema::drop('membership_types');
    }
}
