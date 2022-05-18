<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJwtAuthColumnsToSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedulers', function (Blueprint $table) {
            $table->text('jwt')->nullable();
            $table->dateTime('jwt_expire_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedulers', function (Blueprint $table) {
            $table->dropColumn('jwt');
            $table->dropColumn('jwt_expire_date');
        });
    }
}
