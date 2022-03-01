<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->string('method');
            $table->string('query_id');
            $table->string('region');
            $table->string('region_id');
            $table->string('word');
            $table->timestamps();
        });
    }
    protected $fillable = ['project_id','method','query_id','region','project_id','word'];

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_positions');
    }
}
