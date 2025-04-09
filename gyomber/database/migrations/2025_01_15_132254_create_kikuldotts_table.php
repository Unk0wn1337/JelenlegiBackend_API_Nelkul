<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kikuldotts', function (Blueprint $table) {
            $table->bigIncrements('kikuldott_azon');
            $table->unsignedBigInteger('dolgozo_azon');
            $table->string('pdf_fajl_neve');
            $table->dateTime('kuldes_datuma'); // most ennek kéne a timestamps-nek lennie
        

            $table->foreign('dolgozo_azon')->references('d_azon')->on('dolgozos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kikuldotts');
    }
};
