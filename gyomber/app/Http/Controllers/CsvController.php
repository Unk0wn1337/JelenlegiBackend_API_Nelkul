<?php

namespace App\Http\Controllers;

use App\Models\dolgozo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//1. js-el
class CsvController extends Controller
{   


    public function saveJsonToDatabase(Request $request)    // request objektumot kap paraméterként, a kliens által küldött adatok
    {

        $request->validate([
            'json' => 'required|json',
        ]);


        $students = json_decode($request->json, true);  // a json adatot egy PHP tombbé alakitjuk, (ture->asszociativ tömb, nem indexek hanem kulcs-érték alapján)

        foreach ($students as $studentData) {
            // ha barmelyik hianyzik vagy nem stimmel, akkor az adott hallgató nem kerül mentésre
            //if (isset($studentData['d_azon']) && isset($studentData['nev']) && isset($studentData['email'])) {  

             // Adatvalidálás az egyes hallgatók adataira
            $validator = Validator::make($studentData, [
                'd_azon' => 'required|unique:dolgozos,d_azon',  // Egyedi azonosító, nem lehet duplikált
                'nev' => 'required|string|max:255',
                'email' => 'required|email|unique:dolgozos,email', // Email kötelező, és egyedi
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Hibás adatok találhatók!',
                    'errors' => $validator->errors()
                ], 400);
            }

            dolgozo::create([
                'd_azon' => $studentData['d_azon'],
                'nev' => $studentData['nev'],
                'email' => $studentData['email'],
            ]);
            
        }
        

        return response()->json(['message' => 'Sikeres mentés!']);
    }
}



