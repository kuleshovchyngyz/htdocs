<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadyScheduledPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ready_scheduled_positions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('filter');
            $table->string('google')->nullable();
            $table->string('query_group');
            $table->string('yandex')->nullable();
            $table->string('project_id')->nullable();
            $table->integer('task_id');
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
        Schema::dropIfExists('ready_scheduled_positions');
    }
}
