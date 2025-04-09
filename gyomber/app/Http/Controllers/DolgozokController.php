<?php

namespace App\Http\Controllers;

use App\Models\dolgozo;
use App\Models\gyakorlatihely;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DolgozokController extends Controller
{
    public function index()
    {
        $query = DB::table('dolgozos')->get();
        return $query;
    }





    public function DolgozoIdSzerint($id)
    {
        $query = DB::table('dolgozos')
            ->select('*')
            ->where('d_azon', '=', $id)
            ->first();
        
        return response()->json($query);
    }



    public function getDolgozokTobbMintEgyKuldottPdf()
    {
        $query = DB::table('dolgozos as d')
            ->join('kikuldotts as k', 'd.d_azon', '=', 'k.dolgozo_azon') 
            ->select('d.nev', DB::raw('COUNT(k.kikuldott_azon) as kuldott_pdf_szama'))
            ->groupBy('d.d_azon', 'd.nev')
            ->havingRaw('COUNT(k.kikuldott_azon) > 1')
            ->get();

        return $query;
    }



    public function getDolgozokUtolsoKuldottPdfDatum()
    {
        $query = DB::table('dolgozos as d')
            ->join('kikuldotts as k', 'd.d_azon', '=', 'k.dolgozo_azon')
            ->select('d.nev', DB::raw('MAX(k.kuldes_datuma) as utolso_kuldott_datum'))
            ->groupBy('d.nev')
            ->get();

        return $query;
    }





    public function getDolgozokSzamaGyakorlatiHelyenkent()
    {
        $query = DB::table('dolgozos as d')
            ->join('gyakorlatihelies as g', 'd.gyakhely_azon', '=', 'g.gyak_azon')
            ->select('g.ceg_nev', DB::raw('COUNT(DISTINCT d.d_azon) as dolgozos_szama'))
            ->groupBy('g.ceg_nev')
            ->get();

        return $query;
    }








    /*public function getDolgozoskTobbMintEgyGyakorlatiHely()
    {
        $query = DB::table('dolgozos as d')
            ->join('gyakorlatihely as g', 'd.gyakhely_azon', '=', 'g.gyak_azon')
            ->select('d.nev', DB::raw('COUNT(g.gyak_azon) as gyakorlati_helyek_szama'))
            ->groupBy('d.nev')
            ->having('gyakorlati_helyek_szama', '>', 1)
            ->get();

        return $query;
    }*/

    public function getDolgozokEsIskolajuk()
    {
        $query = DB::table('dolgozos as d')
            ->join('iskolas as i', 'd.iskola_azon', '=', 'i.isk_azon') 
            ->select('d.nev', 'i.nev as iskolanev')
            ->get();

        return $query;
    }



    








    public function nincsCeg()
    {
        $query = DB::table('dolgozos as d')
        ->whereNull('gyakhely_azon')
        ->select('d.nev')
        ->get();
        return $query;
    }

    public function adminTeszt()
    {
        $query = DB::table('users')->get();
        return $query;
    }







    public function getDolgozok()
    {

        return response()->json(dolgozo::all());
    }


    // a studentsmanagement oldalon a táblázathoz amikor frissitjuk az adatot
    public function updateDolgozo(Request $request, $id)
    {
        $dolgozo = dolgozo::find($id);

        if (!$dolgozo) {
            return response()->json(['message' => 'Dolgozó nem található'], 404);
        }

        $dolgozo->update($request->all());

        return response()->json(['message' => 'Dolgozó adatai frissítve!', 'dolgozo' => $dolgozo]);
    }

    // naplozashoz
    // Kik azok a diákok, akik még nem kaptak PDF-et ebben a hónapban? 
   // (kijavítani: mindig a következő honapba kuldjuk ki az elozo havit)
    public function NemKaptakEbbenHonapbanPdfet(){
        // $currentMonth = Carbon::now()->month;
        // $query = dolgozo::leftJoin('kikuldotts', 'dolgozos.d_azon', '=', 'kikuldotts.dolgozo_azon')
        //     ->whereNull('kikuldotts.kuldes_datuma')
        //     ->orWhereMonth('kikuldotts.kuldes_datuma', '!=', $currentMonth)
        //     ->select('kikuldotts.kikuldott_azon', 'kikuldotts.dolgozo_azon')
        //     ->get();
        $query = DB::select("
        SELECT kikuldotts.kikuldott_azon, kikuldotts.dolgozo_azon, kikuldotts.pdf_fajl_neve, kikuldotts.kuldes_datuma
        FROM dolgozos
        LEFT JOIN kikuldotts ON dolgozos.d_azon = kikuldotts.dolgozo_azon
        WHERE 
        kikuldotts.pdf_fajl_neve IS NULL
        OR kikuldotts.kuldes_datuma IS NULL
        OR YEAR(kikuldotts.kuldes_datuma) != YEAR(NOW())
        OR MONTH(kikuldotts.kuldes_datuma) != MONTH(NOW())
    ");
    

        return $query;
    }

    public function KiMikorKapottLegutoljaraPenzugyiDokumentumot(){
        $query = DB::select("
            SELECT k.kikuldott_azon, k.dolgozo_azon, k.pdf_fajl_neve, k.kuldes_datuma, d.nev as nev
            FROM dolgozos d
            JOIN kikuldotts k ON d.d_azon = k.dolgozo_azon
            WHERE k.kuldes_datuma = (
                SELECT MAX(k2.kuldes_datuma)
                FROM kikuldotts k2
                WHERE k2.dolgozo_azon = d.d_azon
            )
            ORDER BY k.kuldes_datuma DESC;
        ");
        return $query;

    }

    public function AkiKimaradtAKikuldesbol(){
        $currentYear = Carbon::now()->year;

        $query = DB::table('dolgozos')
            ->leftJoin('kikuldotts', function($join) use ($currentYear) {
                $join->on('dolgozos.d_azon', '=', 'kikuldotts.dolgozo_azon')
                    ->whereYear('kikuldotts.kuldes_datuma', $currentYear);
            })
            ->whereNull('kikuldotts.dolgozo_azon')
            ->distinct()
            ->pluck(DB::raw('MONTH(kikuldotts.kuldes_datuma)'));

        return $query;
    }



    public function deleteDolgozo($id)
    {
        $dolgozo = Dolgozo::find($id);

        if (!$dolgozo) {
            return response()->json(['error' => 'Diák nem található!'], 404);
        }

        $dolgozo->delete();

        return response()->json(['message' => 'Diák sikeresen törölve!'], 200);
    }


}