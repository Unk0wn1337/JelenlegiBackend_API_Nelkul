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
        Schema::create('dolgozos', function (Blueprint $table) {
            $table->id('d_azon');
            $table->unsignedBigInteger('penzugyi_azon')->unique()->nullable();
            $table->string('nev');
            $table->string('email')->unique();
            $table->string('szul_nev')->nullable();
            $table->string('születesi_hely')->nullable();
            $table->date('születesi_ido')->nullable();
            $table->string('anyaja_neve')->nullable();
            $table->string('taj_szam')->unique()->nullable();
            $table->string('ado_szam')->unique()->nullable();
            $table->string('gondviselo_nev')->nullable();
            $table->string('telefonszam')->nullable();
            $table->enum('csoport_azon', ['diák', 'tanár'])->nullable();
            $table->string('isk_osztály')->nullable();
            $table->string('akk_csoport')->nullable();

            $table->unsignedBigInteger('iskola_azon')->nullable();
            $table->unsignedBigInteger('gyakhely_azon')->nullable();
            
            $table->text('megjegyzes')->nullable();
            $table->timestamps();

            

            $table->foreign('iskola_azon')->references('isk_azon')->on('iskolas')->onDelete('set null');
            $table->foreign('gyakhely_azon')->references('gyak_azon')->on('gyakorlatihelies')->onDelete('set null');
        });
    }  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dolgozos');
    }
};
