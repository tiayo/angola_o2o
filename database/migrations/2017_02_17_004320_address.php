<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Address extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("wp_addresses", function (Blueprint $table) {
            $table->increments("id");
            $table->text("address");
            $table->string("first_name");
            $table->string("last_name");
            $table->string("phone_number");
            $table->boolean("is_default");
            $table->string('country')->default("Angola");
            $table->string('state')->default('Cabinda');
            $table->string('city');
            $table->integer("user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("wp_addresses");
    }
}
