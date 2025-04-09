<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gyakorlatihely extends Model
{
    use HasFactory;

    protected $primaryKey = 'gyak_azon';

    protected $fillable = [
        'ceg_nev',
        'web_oldal',
        'kapcsolat_tarto',
        'telefonszam',
    ];


    public function dolgozo()
    {
        return $this->hasMany(dolgozo::class, 'gyakhely_azon');
    }
}
