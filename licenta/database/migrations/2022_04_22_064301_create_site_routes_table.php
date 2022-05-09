<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Site;

class CreateSiteRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Site::class, 'site_id')->onDelete('cascade');
            $table->text('route');
            $table->string('http_code');
            $table->string('found_on')->nullable();
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
        Schema::dropIfExists('site_routes');
    }
}
