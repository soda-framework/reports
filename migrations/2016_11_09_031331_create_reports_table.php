<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description', 65535)->nullable();
            $table->string('class');
            $table->integer('application_id')->unsigned()->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->integer('times_ran')->unsigned()->nullable();
            $table->timestamp('last_run_at');
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
        Schema::drop('reports');
    }
}
