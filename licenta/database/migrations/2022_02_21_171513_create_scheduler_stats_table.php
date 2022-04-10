<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\MOdels\Scheduler::class,'scheduler_id');
            $table->text("response_body");
            $table->integer("status_code");
            $table->text('headers');
            $table->text('handler_stats')->nullable();
            $table->double('transfer_time')->nullable();
            $table->dateTime("executed_at")->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->double('duration')->nullable();
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
        Schema::dropIfExists('scheduler_stats');
    }
}
