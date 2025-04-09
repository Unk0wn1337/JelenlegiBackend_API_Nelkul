<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;





class AdminMiddleware
{
    // handle minden admin kéréskor lefut, a requestz az összes kérési infot tarltamzza(személy,paraméterek), 
    // a next csak továbbírányítja a feldolgozást, utolsó Response->ami vissza leszadva feldolgozás után
    public function handle(Request $request, Closure $next): Response
    {

        // be van e jelentkezve és rendelkezik e admin jogosultsagal
        if (Auth::check() && Auth::user()->jogosultsag_azon === 1) {
            return $next($request);
        }
        
        return response()->json(['message' => 'Hozzáférés megtagadva!'], 403);
    }
}



// php artisan config:clear
// php artisan cache:clear
// php artisan serve