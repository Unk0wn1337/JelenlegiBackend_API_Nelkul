<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iskola extends Model
{
    use HasFactory;

    protected $primaryKey = 'isk_azon';


    protected $fillable = [
        'nev',
        'web_oldal',
        'kapcsolat_tarto',
        'telefonszam'
    ];


    public function dolgozo()
    {
        return $this->hasMany(Dolgozo::class, 'iskola_azon');
    }
}
