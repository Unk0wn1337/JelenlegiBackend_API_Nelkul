<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JogosultsagConstraint extends Migration
{

    # a jogosultság_azon mező csak 1 és 3 közötti számokat tartalmazhat

    /**
     * A migráció végrehajtása.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE users ADD CONSTRAINT check_jogosultsag CHECK (jogosultsag_azon BETWEEN 1 AND 3);');
    }

    /**
     * A migráció visszavonása.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE users DROP CONSTRAINT check_jogosultsag;');
    }
}