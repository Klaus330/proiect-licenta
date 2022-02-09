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
            $table->double("dns_lookup");
            $table->integer("http_code");
            $table->integer("redirect_count");
            $table->double("redirect_time");
            $table->double("total_time");
            $table->double("connect_time");
            $table->double("speed_download");
            $table->double("size_download");
            $table->double("header_size");
            $table->double("request_size");
            $table->string("content_type");
            $table->double("content_length");
            $table->double("primary_port");
            $table->double("pretransfer_time");
            $table->double("appconnect_time");
            $table->double("start_transfer_time");
            $table->string("server");
            $table->string("date");
            $table->string("connection");
            $table->string("protocol_version");
            $table->string("http_version");
            $table->string('scheme');
            $table->string('user_agent');
            $table->string('reason_phrase');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->double('duration');
            $table->text('headers');
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
