<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\DolgozokController;
use App\Http\Controllers\PdfAthelyezController;
use App\Http\Controllers\PdfTorlesController;
use App\Http\Controllers\StudentMailController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// shift + alt + a = több sor kommentelése
// ---> nem... Ctrl + K + C, ami kivan jelölve





// mindenki hozzáférhet
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);/*  */
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);



// // a bejelentkezett felhasználó férhet hozzá, jogosultsag = 2
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/munkanelkuli', [DolgozokController::class, 'nincsCeg']);
    Route::get('/index', [DolgozokController::class, 'index']);
    Route::get('/dolgozo-id-szures/{id}', [DolgozokController::class, 'DolgozoIdSzerint']);
    Route::get('/dolgozok-tobb-mint-egy', [DolgozokController::class, 'getDolgozokTobbMintEgyKuldottPdf']);

    Route::get('/dolgozok-utolso-kuldott-pdf-datum', [DolgozokController::class, 'getDolgozokUtolsoKuldottPdfDatum']);
    Route::get('/dolgozok-szama-gyakorlati-helyenkent', [DolgozokController::class, 'getDolgozokSzamaGyakorlatiHelyenkent']);
    Route::get('/dolgozok-es-iskolajuk', [DolgozokController::class, 'getDolgozokEsIskolajuk']);


    // Route::apiResource('/felhasznalok', UserController::class);
    Route::get('/levelek', [StudentMailController::class, 'getLevelek']);
    Route::post('/save-json-to-database', [CsvController::class, 'saveJsonToDatabase']);
    // feltoltott fajl athelyezes backendre // ez kell hozzá !!!! php artisan storage:link   !!!
    Route::post('/relocate', [PdfAthelyezController::class, 'relocate']);
    Route::delete('/torol-pdf-fajlok', [PdfTorlesController::class, 'torolKuldendoFajlok']);
    Route::post('/get-emails', [StudentMailController::class, 'getEmails']);
    Route::post('/send-email', [StudentMailController::class, 'sendEmail']);
    Route::get('/dolgozok', [DolgozokController::class, 'getDolgozok']);
    Route::put('/dolgozok/{id}', [DolgozokController::class, 'updateDolgozo']);
    Route::delete('/dolgozok/{id}', [DolgozokController::class, 'deleteDolgozo']);
    Route::get('/nem-kaptak-ebben-a-honapban-pdf',[DolgozokController::class, 'NemKaptakEbbenHonapbanPdfet']);
    Route::get('/ki-mikor-kapott-legutoljara-penzugyi-dokumentumot',[DolgozokController::class, 'KiMikorKapottLegutoljaraPenzugyiDokumentumot']);
    Route::get('/aki-kimaradt-kikuldesbol',[DolgozokController::class, 'AkiKimaradtAKikuldesbol']);
    Route::get('/listFiles',[PdfAthelyezController::class, 'listFiles']);
   
    // Route::apiResource('/dolgozok', UserController::class);
});



// csak az admin férhet hozzá, jogosultsag = 1
Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::post('/admin/felvisz-felhasznalo/{name}/{email}/{password}/{level}',[AdminController::class, 'felvitel']);
    Route::put('/admin/modosit/{id}/{szint}',[AdminController::class, 'modositJog']);


    Route::get('/felhasznalok', [UserController::class, 'getFelhasznalok']);
    Route::put('/felhasznalok/{id}', [UserController::class, 'updateFelhasznalo']);
    Route::delete('/felhasznalok/{id}', [UserController::class, 'deleteFelhasznalo']);

});









/* ÁLTALÁNOS KÖTELEZŐ LETÖLTÉSEK */
// composer require laravel/sanctum
// composer require laravel/breeze --dev
// php artisan breeze:install api
// php artisan storage:link




// utvonalak ujraírás miatt - letisztázás:
// php artisan route:list
// 1. php artisan route:clear
// 2. php artisan config:cache
// 3. php artisan serve



// trigger - migrációs táblában - email kuldes-nél figyeli hogy akinek kikuldjuk, az tényleg létezik (az adatbázisban)
// observer - Providers mappa, Observers mappa,  - ha valaki regisztrál akkor email küld róla a rendszergazdának
// constraint - migrációs táblában - jogosultsag csak 1-3 között lehet




/*


- studentstáblára keresést gombot írni
- kikuldott leveleknél több keresés gombot írni


*/    