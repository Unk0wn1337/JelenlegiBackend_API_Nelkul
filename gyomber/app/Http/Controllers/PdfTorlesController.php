<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;

class PdfTorlesController extends Controller
{  


    public function torolKuldendoFajlok()
    {
        $directory = 'public/kuldendoFajlok';
        
        if (Storage::exists($directory)) {

            $files = Storage::allFiles($directory);

            Storage::delete($files);

            Storage::makeDirectory($directory); // ujramkészítjük az üres mappát

            
            return response()->json(['message' => 'A fájlok sikeresen törölve lettek.', 'deleted_count' => count($files)]);
        }

        return response()->json(['message' => 'A mappa nem létezik.', 'deleted_count' => 0], 404);
    }

}