<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CreateDeleteOldKikuldottTrigger extends Migration
{
    public function up()
    {
        // Trigger -- Ellenőrizzük, hogy létezik-e a dolgozó a dolgozos táblában
        DB::unprepared('
            CREATE TRIGGER check_dolgozo_exists
            BEFORE INSERT ON kikuldotts
            FOR EACH ROW
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM dolgozos WHERE d_azon = NEW.dolgozo_azon) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "A dolgozó nem létezik!";
                END IF;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS check_dolgozo_exists');
    }

    // SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "A dolgozó nem létezik!";


    /*public function up()
    {
        // sql-ben megíra a trigger
        // -- Töröljük a régi rekordokat, amelyek 24 hónapnál régebbiek, és nem érintjük az újonnan beszúrt rekordot
        DB::unprepared('
            CREATE TRIGGER delete_old_kikuldott
            AFTER INSERT ON kikuldotts
            FOR EACH ROW
            BEGIN
                DELETE FROM kikuldotts
                WHERE kuldes_datuma < DATE_ADD(NOW(), INTERVAL -24 MONTH)
                AND kuldes_datuma != NEW.kuldes_datuma;
            END;
        ');
    }

    public function down()
    {
        // ha el kéne távolítani a  triggert, vész esetre
        DB::unprepared('DROP TRIGGER IF EXISTS delete_old_kikuldott');
    }*/



    /*
    // a dolgozo_azon szám szerepeljen a pdf_fajl_neve mezőben, amikor új rekordot próbálsz beszúrni a táblába
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER check_pdf_file_name
            BEFORE INSERT ON kikuldotts
            FOR EACH ROW
            BEGIN
                -- Ellenőrizzük, hogy a pdf_fajl_neve tartalmazza-e a dolgozo_azon számot
                IF NOT LIKE NEW.pdf_fajl_neve, CONCAT('%', NEW.dolgozo_azon, '%') THEN
                    -- Ha nem található, hibát dobunk
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "A pdf_fajl_neve-nak tartalmaznia kell a dolgozo_azon számot!";
                END IF;
            END;
        ');
    }

    public function down()
    {
        // A trigger eltávolítása, ha szükséges
        DB::unprepared('DROP TRIGGER IF EXISTS check_pdf_file_name');
    }
    */
}


// DELETE FROM kikuldotts WHERE kuldes_datuma < DATE_ADD(NOW(), INTERVAL -24 MONTH);