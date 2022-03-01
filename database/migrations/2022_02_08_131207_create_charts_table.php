<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('summary_project_id')->nullable();
            $table->string('summary_start_date')->nullable();
            $table->string('summary_end_date')->nullable();
            $table->string('summary_type_widget')->nullable();
            $table->string('summary_search_engine')->nullable();
            $table->integer('summary_region_id')->nullable();
            $table->string('summary_date_get')->nullable();
            $table->string('summary_result')->nullable();
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
        Schema::dropIfExists('charts');
    }
}
