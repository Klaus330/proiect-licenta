<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedulers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('method');
            $table->foreignIdFor(\App\Models\Site::class,'site_id');
            $table->string("endpoint");
            $table->string("period")->nullable();
            $table->boolean("alerts")->default(false);
            $table->integer("failure_number")->nullable();
            $table->string("cronExpression");
            $table->dateTime("next_run");
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
        Schema::dropIfExists('schedulers');
    }
}
