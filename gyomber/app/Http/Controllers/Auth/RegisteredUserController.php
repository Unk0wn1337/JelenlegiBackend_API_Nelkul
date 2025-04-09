<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->merge([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'jogosultsag_azon' => (int) $request->jogosultsag_azon,  // kovertaljuk számertekké
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed',  'string', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[!@#$%^&*(),.?":{}|<>]/', Rules\Password::defaults()],
            'jogosultsag_azon' => ['required', 'integer', 'in:1,2,3'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            //'password' => Hash::make($request->string('password')),
            'password' => Hash::make($request->password),
            //'jogosultsag_azon' => 3,
            'jogosultsag_azon' => $request->jogosultsag_azon,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
