<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->integer('yandex_position')->nullable();
            $table->date('yandex_date')->nullable();
            $table->integer('google_position')->nullable();
            $table->date('google_date')->nullable();
            $table->string('task_id')->nullable();
            $table->string('status')->nullable();
            $table->string('method')->nullable();

            $table->foreignId('project_id')->default(0)->nullable();
            $table->foreignId('region_id')->default(0)->nullable();
            $table->foreignId('query_id')->default(0)->nullable();
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
        Schema::dropIfExists('positions');
    }
}
