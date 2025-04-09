<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $query = DB::table('users')->get();
        return $query;
    }






    public function getFelhasznalok()
    {
        return response()->json(User::all());
    }


    // az usersmanagement oldalon a táblázathoz amikor frissitjuk az adatot
    public function updateFelhasznalo(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Dolgozó nem található'], 404);
        }

        $user->update($request->all());

        return response()->json(['message' => 'Dolgozó adatai frissítve!', 'user' => $user]);
    }





    
    public function deleteFelhasznalo($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
