<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_stats', function (Blueprint $table) {
            $table->id();
            $table->double("dns_lookup")->nullable();
            $table->integer("http_code")->nullable();
            $table->integer("redirect_count")->nullable();
            $table->double("redirect_time")->nullable();
            $table->double("total_time")->nullable();
            $table->double("connect_time")->nullable();
            $table->double("speed_download")->nullable();
            $table->double("size_download")->nullable();
            $table->double("header_size")->nullable();
            $table->double("request_size")->nullable();
            $table->string("content_type")->nullable();
            $table->double("content_length")->nullable();
            $table->double("primary_port")->nullable();
            $table->double("pretransfer_time")->nullable();
            $table->double("appconnect_time")->nullable();
            $table->double("start_transfer_time")->nullable();
            $table->string("server")->nullable();
            $table->string("date")->nullable();
            $table->string("connection")->nullable();
            $table->string("protocol_version")->nullable();
            $table->string("http_version")->nullable();
            $table->string('scheme')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('reason_phrase')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->double('duration')->nullable();
            $table->text('headers')->nullable();
            $table->text('body')->nullable();
            $table->foreignIdFor(\App\Models\Site::class, 'site_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('site_stats');
    }
}
