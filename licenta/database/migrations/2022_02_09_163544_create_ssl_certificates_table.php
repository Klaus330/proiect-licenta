<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSslCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ssl_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Site::class, 'site_id')->constrained()->onDelete('cascade');
            $table->text('extensions');
            $table->text('purposes');
            $table->string('signatureTypeSN');
            $table->string('signatureTypeLN');
            $table->string('serialNumber');
            $table->string('serialNumberHex');
            $table->string('version');
            $table->string('hash');
            $table->string('subject');
            $table->string("name");
            $table->text('issuer');
            $table->datetime('validTo');
            $table->datetime('validFrom');
            $table->string('signatureTypeNID');
            $table->integer("expires")->default(10);
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
        Schema::dropIfExists('ssl_certificates');
    }
}
