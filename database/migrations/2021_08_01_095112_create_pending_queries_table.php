<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_queries', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('word');
            $table->integer('query_id');
            $table->integer('region_id');
            $table->integer('project_id');
            $table->string('failed')->default('0');
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
        Schema::dropIfExists('pending_queries');
    }
}
