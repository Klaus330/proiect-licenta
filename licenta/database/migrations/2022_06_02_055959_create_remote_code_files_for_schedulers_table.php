<?php

use App\Models\Scheduler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteCodeFilesForSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_code', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Scheduler::class)->onCascadeDelete();
            $table->string('path');
            $table->string('language')->default('javascript');
            $table->string('filename');
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
        Schema::dropIfExists('remote_code');
    }
}
