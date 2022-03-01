<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_region', function (Blueprint $table) {
            $table->integer('chart_id')->unsigned();
            $table->integer('region_id')->unsigned();
            $table->foreign('chart_id')->references('id')->on('charts')
                ->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')
                ->onDelete('cascade');
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
        Schema::dropIfExists('chart_region');
    }
}
