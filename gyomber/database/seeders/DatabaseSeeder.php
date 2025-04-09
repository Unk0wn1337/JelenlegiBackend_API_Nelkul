<?php

namespace Database\Seeders;

use App\Models\dolgozo;
use App\Models\felhasznalo;
use App\Models\gyakorlatihely;
use App\Models\iskola;
use App\Models\jogosultsag;
use App\Models\kikuldott;
use App\Models\kikuldotts;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        

        $iskolak = iskola::factory(5)->create();
        $gyakhelyek = gyakorlatihely::factory(5)->create();



        for ($i = 0; $i < 20; $i++) {
            dolgozo::factory()->create([
                'iskola_azon' => $iskolak->random()->isk_azon,
                'gyakhely_azon' => $gyakhelyek->random()->gyak_azon,
            ]);
        }

        
        kikuldotts::factory(8)->create();
        




        User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminpassword'),
            'name' => 'Admin',
            'jogosultsag_azon' => 1,
        ]);
        
       
        User::create([
           'email' => 'user@gmail.com',
           'password' => Hash::make('userpassword'),
           'name' => 'User_1',
           'jogosultsag_azon' => 2,
       ]);


       User::factory(6)->create();

    }
}
