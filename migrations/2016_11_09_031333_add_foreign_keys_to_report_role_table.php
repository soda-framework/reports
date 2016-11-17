<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReportRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_role', function (Blueprint $table) {
            $table->foreign('report_id')->references('id')->on('reports')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_role', function (Blueprint $table) {
            $table->dropForeign('report_role_report_id_foreign');
            $table->dropForeign('report_role_role_id_foreign');
        });
    }

}
