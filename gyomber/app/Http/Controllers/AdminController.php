<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function modositJog($id,$permission){
        $admin = Auth::admin();
        $query = DB::table('users')
            ->where('id',"=" ,$id)
            ->update(['jogosultsag_azon' => $permission]);
            
    }
    public function felvitel($name,$email,$password,$level){
        $admin = Auth::admin();
        $query = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password), 
            'jogosultsag_azon' => $level, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
            
    }
}
