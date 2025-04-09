<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dolgozo extends Model
{
    use HasFactory;


    protected $primaryKey = 'd_azon';
    public $incrementing = false; // kikapcsoljuk az autoincrementet
    protected $keyType = 'int';     // integer tipusu legyen

    protected $fillable = [
        'd_azon',
        'penzugyi_azon',
        'nev',
        'email',
        'szul_nev',
        'születesi_hely',
        'születesi_ido',
        'anyaja_neve',
        'taj_szam',
        'ado_szam',
        'gondviselo_nev',
        'telefonszam',
        'csoport_azon',
        'isk_osztály',
        'akk_csoport',
        'iskola_azon',
        'gyakhely_azon',
        'megjegyzes'
    ];



    // kulso tablakhoz kapcsolat
    public function iskola()
    {
        return $this->belongsTo(Iskola::class, 'iskola_azon');
    }

    public function gyakhely()
    {
        return $this->belongsTo(Gyakorlatihely::class, 'gyakhely_azon');
    }



    protected $hidden = [
        // Ezek mindig el lesznek rejtve -------------------------------- én írtam bele
        'created_at',
        'updated_at'
    ];
}
