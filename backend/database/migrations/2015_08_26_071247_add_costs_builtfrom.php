<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostsBuiltfrom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {

            $table->integer('cost');

        });

        Schema::create('items_builts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('from_item_id')->nullable();
            $table->foreign('from_item_id')
                  ->references('id')->on('items')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');


            $table->unsignedBigInteger('to_item_id')->nullable();
            $table->foreign('to_item_id')
                  ->references('id')->on('items')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

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
        Schema::table('items', function (Blueprint $table) {

            $table->dropColumn('cost');

        });
        Schema::drop('items_builts');
    }
}
