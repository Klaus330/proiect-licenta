<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesOnTimestampsColumnInSiteStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_stats', function (Blueprint $table) {
            $table->index(['created_at', 'ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_stats', function (Blueprint $table) {
            $table->dropIndex(['created_at', 'ended_at']);
        });
    }
}
