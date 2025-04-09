<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jogosultsag_azon' => 3,
            'email_verified_at' => now(),   // mivel nalunk nincs email verifikacio, ezért now()ra beállítjuk
        ]);

        return response()->json(['message' => 'Registration successful, awaiting admin approval'], 201);
    }
}


// ez a fájl nem csinál semmit mert az Auth mappában van az igazi....