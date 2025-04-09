<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class PdfAthelyezController extends Controller
{
    public function relocate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:5120'    # nemüres/fájl/pdf/5MB = 5120 KB max
        ]);

        // megnezzuk hogy a kérésbe érkezett e fájl és hogy nem e sérült és rendben van e
        if ($request->hasFile('file') && $request->file('file')->isValid()) {


            $file = $request->file('file');     // elmentjuk a fajlt egy valtozoba
            $filename = $file->getClientOriginalName(); // eredeti fájlnevet lekérjük

            $path = $file->storeAs('kuldendoFajlok', $filename, 'public');  // (mappa,fájlnév,tárhely)


            return response()->json([
                'message' => 'Fájl sikeresen feltöltve!',
                'path' => $path,
                'filename' => $filename
            ]);
        } else {
            return response()->json([
                'message' => 'Hiba történt a fájl feltöltésekor.'
            ], 400);
        }
    }






    public function listFiles(){

        $directory = 'public/kuldendofajlok'; 
        $files = Storage::files($directory);


        $fileNames = [];

        foreach ($files as $file) {
            $fileNames[] = basename($file); // az utvonal nelkuli fajlnevet kerjuk csak
        }

        return response()->json($fileNames);
    }
}