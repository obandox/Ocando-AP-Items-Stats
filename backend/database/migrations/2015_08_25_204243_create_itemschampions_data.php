<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemschampionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('itemId');
            $table->string('name');
            $table->string('group');
            $table->string('description');

            $table->timestamps();
        });

        Schema::create('champions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('championId');
            $table->string('name');
            $table->string('key');
            $table->string('title');

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
        Schema::drop('items');
        Schema::drop('champions');
    }
}
