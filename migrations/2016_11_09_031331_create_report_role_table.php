<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_role', function (Blueprint $table) {
            $table->integer('report_id')->unsigned();
            $table->integer('role_id')->unsigned()->nullable()->index('report_role_role_id_foreign');
            $table->primary(['report_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report_role');
    }
}
