<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use App\Mail\StudentEmail;
use App\Models\dolgozo;
use App\Models\kikuldott;
use App\Models\kikuldotts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentMailController extends Controller
{



    // a konkrét email küldés itt történik az összes személynek
    public function sendEmail()
    {
        set_time_limit(500);        //maximális futási a fuggvenyre, mert az email kuldest csak 60mp-ig engedi

        $jsonFilePath = storage_path('app/jsonScriptek/studentEmailData.json');


        if (!Storage::exists('jsonScriptek/studentEmailData.json')) {
            return response()->json(['message' => 'JSON fájl nem található!'], 400);
        }

        $jsonContent = file_get_contents($jsonFilePath);    // json fájl tartalmát beolvassa
        $emails = json_decode($jsonContent, true);  // asszociativ tombé alakitjuk
    
        $chunks = array_chunk($emails, 30); // 50-esével fogjuk kuldeni, ezrt 50db-ra felosztjuk

        $db = 0;
        $missingFiles = [];
        $successfulEmails = [];
        
        foreach ($chunks as $emailChunk) {
            foreach ($emailChunk as $email) {
                $validator = Validator::make($email, [
                    'd_azon' => 'required|integer',
                    'nev' => 'required|string|max:255',
                    'email' => 'required|email',
                    'fajlNev' => 'required|string'
                ]);
        
                if ($validator->fails()) {
                    continue;
                }


                $mailData = [
                    'd_azon' => $email['d_azon'],
                    'name' => $email['nev'],
                    'email' => $email['email'],
                    'pdf_name' => $email['fajlNev'],
                    'path' => storage_path('app/public/kuldendoFajlok/' . $email['fajlNev'])
                ];


        
                if (file_exists($mailData['path'])) {       // ha a szukseges pdf fajl megtalálható
                    try {
                        Mail::to($email['email'])->send(new StudentEmail($mailData));
                        $db++;

                        $successfulEmails[] = $email['email'];

                        DB::table('kikuldotts')->insert([
                            'dolgozo_azon' => $email['d_azon'],
                            'pdf_fajl_neve' => $email['fajlNev'],
                            'kuldes_datuma' => now(),
                        ]);

                    } catch (\Exception $e) {
                        $missingFiles[] = $email['email'];
                    }
                } else {
                    $missingFiles[] = $mailData['path'];
                }
            }

        sleep(3);
        }


        return response()->json([
            'message' => "Összesen {$db} e-mail elküldve.",
            'sent_count' => $db,
            'missing_files' => !empty($missingFiles) ? $missingFiles : null,
            'successful_emails' => $successfulEmails,
        ], 200);
    }












    // a kódokhoz tartózó emailcímeket és neveket lekéri adatbázisól majd egy jsnoba elmenti
    public function getEmails(Request $request)
    {
        $fileDetails = $request->input('fileDetails');  // a frontendrol kuldott tömböt kinyerjuk, alul a példa:

        /*
        fileDetails:
        [
            { "kod": "123", "fajlnev": "abc1.pdf" },
            { "kod": "456", "fajlnev": "abc2.pdf" }
        ]
        */
        
        if (!$fileDetails || !is_array($fileDetails)) {
            return response()->json(['message' => 'Érvénytelen fájl részletek.(üres vagy nem tömb)'], 400);
        }



        // egyesevel validalunk
        foreach ($fileDetails as $file) {
            $validator = Validator::make($file, [
                'kod' => 'required|string|max:5',
                'fileName' => 'required|string|max:255',
            ]);
        
            if ($validator->fails()) {
                return response()->json([
                    'message' => "Hibás adat a fileName/kod-ban.",
                    'errors' => $validator->errors()
                ], 400);
            }
        }



        $kodok = array_column($fileDetails, 'kod'); #csak a kódok kinyerése

        $results = dolgozo::whereIn('d_azon', $kodok)->get(['d_azon', 'nev', 'email']);


        #átalakítjuk az adatokat egy JSON tömbbé
        $data = [];

        // az adatbázisból lekért adatokat ossze kapcsoljuk az eredeti fájlnevekkel, az emailhez szükséges,
        // igy egy uj tombbe teszzuk a kiynert adatokat az eredeti fájl nevével együtt
        foreach ($results as $item) {
            $matchingFile = null;
            // megkeressuk a megfelelo fajlt a fajlnevek kozott
            foreach ($fileDetails as $file) {
                if ($file['kod'] == $item->d_azon) {
                    $matchingFile = $file['fileName'];
                    break;
                }
            }

            $data[] = [
                'd_azon' => $item->d_azon,
                'nev' => $item->nev,
                'email' => $item->email,
                'fajlNev' => $matchingFile,
            ];
        }




        $jsonFileName = 'studentEmailData.json';

        Storage::put('jsonScriptek/' . $jsonFileName, json_encode($data, JSON_PRETTY_PRINT));   // jsonfájl létrehozás, és mentése, JSON_PRETTY-> szebb formátum



        return response()->json([
            'message' => 'Adatok lekérve és JSON fájlba mentve.',
            //'file_path' => Storage::url($jsonFilePath),
            'file_path' => Storage::url('jsonScriptek/' . $jsonFileName),
            'data' => $data
        ], 200);
        //dd("MailData adatok amik rosszak is lehetnek akár: ", $mailData);
    }







    public function getLevelek()
    {

        $levelek = kikuldotts::join('dolgozos', 'kikuldotts.dolgozo_azon', '=', 'dolgozos.d_azon')
        ->select('kikuldotts.*', 'dolgozos.nev as nev')
        ->get();

        return response()->json($levelek);
    }
    
}
