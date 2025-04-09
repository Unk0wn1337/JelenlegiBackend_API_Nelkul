<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kikuldotts extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        'kikuldes_azon',
        'penzugy_azon',
        'pdf_fajl_neve',
        'kuldes_datuma',
    ];

}
