<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Mail\StudentEmail;
use App\Models\dolgozo;
use App\Models\gyakorlatihely;
use App\Models\iskola;
use App\Models\kikuldott;
use App\Models\kikuldotts;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    use RefreshDatabase;


    public function test_homepage_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    
    public function test_userEleres()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);
      

        $response = $this->getJson('/api/user');

    
        $response->assertStatus(200);
    }



    public function test_sendsResetPasswordLink()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/forgot-password', [
            'email' => 'user@example.com',
        ]);
    
        $response->assertStatus(200)
                 ->assertJson([
                    'status' => trans(Password::RESET_LINK_SENT),
                 ]);
    }






    public function test_resetsPassword()
    {

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
            'jogosultsag_azon' => 2,
        ]);

        $token = Password::broker()->createToken($user);     // reset token generálás

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => 'user@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);


        $response->assertStatus(200)
                 ->assertJson([
                'status' => trans(Password::PASSWORD_RESET),
        ]);

        // ellenorizzuk hogy tényleg megvaltozott e a jelszo
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }


    
    /*----------------------------------- Az User és az Admin fér hozzá (kezdete) ---------------------------------------------*/

    // akiknek nincs gyakorlatihelyük
    public function test_user_can_get_munkanelkuli()
    {
        $user = User::factory()->create([
            'email' => 'felhasznalo@example.com',
            'password' => Hash::make('jelszo'),
            'jogosultsag_azon' => 2
        ]);
        
        $response = $this->actingAs($user)
                         ->getJson('/api/munkanelkuli');

        $response->assertStatus(200);
    }






    public function test_dolgozoIdSzerint()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);


        $dolgozo = dolgozo::factory()->create([
            'd_azon' => 12345,
            'nev' => 'John Doe',
            'email' => 'john@example.com',
        ]);


        $response = $this->getJson('/api/dolgozo-id-szures/12345');


        $response->assertStatus(200)
                ->assertJson([
                    'd_azon' => 12345,
                    'nev' => 'John Doe',
                    'email' => 'john@example.com',
                ]);
    }






    public function test_dolgozokTobbmintEgyKikuldottPdf()
    {

        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);



        $dolgozo1 = dolgozo::factory()->create([
            'd_azon' => 12345,
            'nev' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    
        $dolgozo2 = dolgozo::factory()->create([
            'd_azon' => 23456,
            'nev' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);
    
        //dd($dolgozo1, $dolgozo2); 
        $kikuldott = kikuldotts::factory(3)->create();
      

        $response = $this->getJson('/api/dolgozok-tobb-mint-egy');

        $response->assertStatus(200);

        //dd($response->json());  ebbe  megnézhetjuk hogy az egyikukre ki fogja dobni hogy 2 pdf-t kapott
    }
    









    public function test_utolsoKuldottDatum()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);



        $dolgozo1 = dolgozo::create([
            'd_azon' => 12345,
            'nev' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $dolgozo2 = Dolgozo::create([
            'd_azon' => 23456,
            'nev' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);


        $kikuldott1 = kikuldotts::factory(1)->create();

        sleep(10);

        $kikuldott2 = kikuldotts::factory(1)->create();


        $response = $this->getJson('/api/dolgozok-utolso-kuldott-pdf-datum');

        $response->assertStatus(200);
        //dd($response->json(), $kikuldott1, $kikuldott2);
    }







    public function test_dolgozokGyakHelyenkent()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);



        $gyakhely1 = gyakorlatihely::create([
            'ceg_nev' => 'Cég 1',
            'web_oldal' => 'https://ceg1.hu',
            'kapcsolat_tarto' => 'Tóth Péter',
            'telefonszam' => '123456789',
        ]);
        
        $gyakhely2 = gyakorlatihely::create([
            'ceg_nev' => 'Cég 2',
            'web_oldal' => 'https://ceg2.hu',
            'kapcsolat_tarto' => 'Kovács Anna',
            'telefonszam' => '987654321',
        ]);


        $dolgozo = dolgozo::factory(3)->create();


        $response = $this->getJson('/api/dolgozok-szama-gyakorlati-helyenkent');

        $response->assertStatus(200);
        //dd($response->json());
    }



    



    public function test_dolgozokIskolaja()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);



        $iskola1 = iskola::create([
            'nev' => 'Iskola 1',
            'web_oldal' => 'https://iskola1.hu',
            'kapcsolat_tarto' => 'Tartó1',
            'telefonszam' => '+36 30 123 4567'
        ]);

        $iskola2 = iskola::create([
            'nev' => 'Iskola 2',
            'web_oldal' => 'https://iskola2.hu',
            'kapcsolat_tarto' => 'Tartó2',
            'telefonszam' => '+36 30 123 4568'
        ]);




        $dolgozo = dolgozo::factory(3)->create();

        $response = $this->getJson('/api/dolgozok-es-iskolajuk');

        $response->assertStatus(200);
        //dd($response->json());
    }



    
    








    // adatbázisba feltültünk jsont, a diákok adatait a csv fájlból
    public function test_it_saves_json_to_database()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);
        
        $jsonData = [
            'json' => json_encode([
                [
                    'nev' => 'John Elton',
                    'email' => 'johnelton@citromail.com',
                    'd_azon' => 53256,
                ]
            ])
        ];

        $response = $this->postJson('/api/save-json-to-database', $jsonData);

        $response->assertStatus(200)
                 ->assertJson([
                'message' => 'Sikeres mentés!' 
            ]);
    }



    


    

    // email küldés csatolt fájllal
    public function test_sends_mail_with_attachment()
    {

        Mail::fake();

        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);


        //$fakeFile = 'testfile.pdf'; 
        $fakeFile = 'Jövedelemkifizetési lap - Kis Pista         (12345)  20241108_1152095.pdf'; 
        
        Storage::fake('public');
        Storage::disk('public')->put('kuldendoFajlok/' . $fakeFile, 'Tartalom a fájlban');


        $emailData = [
            'd_azon' => 12345,
            'nev' => 'Kis Pista',
            'email' => 'kispista@gmail.com',
            'fajlNev' => $fakeFile
        ];

        $response = $this->post('/api/send-email', $emailData);

        $response->assertStatus(200)
                /*->assertJson([
                    'message' => "Összesen 1 e-mail elküldve.", 
                    'sent_count' => 1, 
                    'missing_files' => null, 
                ]);*/;
    }













     // Teszt a fájlok törlésére
    public function test_file_deletion_success()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);

        
        Storage::fake('public');    // létrehozzuk az ál fájlrendszert

        // teszfájlok hozzáadása
        Storage::disk('public')->put('kuldendoFajlok/testfile1.pdf', 'content of file 1');
        Storage::disk('public')->put('kuldendoFajlok/testfile2.pdf', 'content of file 2');


        $response = $this->delete('/api/torol-pdf-fajlok');

        $response->assertJson([
            'message' => 'A fájlok sikeresen törölve lettek.',
        ]);
    }





    // csatolt pdf fájlok áthelyezése
    public function test_file_relocate()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($user);


        Storage::fake('public');

        $file = UploadedFile::fake()->create('testfile.pdf', 100);

        $response = $this->json('POST', '/api/relocate', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Fájl sikeresen feltöltve!',
                ]);
    }

    /*----------------------------------- Az User és az Admin fér hozzá (vége) ---------------------------------------------*/









    /*----------------------------------- Csak az Admin fér hozzá (kezdete) ---------------------------------------------*/

    // összes felhasználó lekérdezése
    public function test_getFelhasznalok()
    {

        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($admin);

        User::factory()->count(2)->create();
        
        $response = $this->getJson('/api/felhasznalok');

        $response->assertStatus(200)
            ->assertJsonCount(3) // megszámolja hogy 3 felhasználo van e a valaszban
            ->assertJsonFragment(['jogosultsag_azon' => 4]); // legalabb az egyiknek a jog.nak 4-nek kell lennie
     }






    // adott felhasználó adatának szerkesztése/frissítése
    public function test_updateFelhasznalo()
    {

        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($admin);


        $updatedData = [
            'name' => 'frissitett név',
            'email' => 'frissmail@gmail.com',
        ];


        $response = $this->putJson("/api/felhasznalok/{$admin->id}", $updatedData);


        $response->assertStatus(200) 
                 ->assertJson([
                     'message' => 'Dolgozó adatai frissítve!',
                     'user' => [
                         'name' => 'frissitett név',
                         'email' => 'frissmail@gmail.com',
                     ],
                 ]);
    }




    public function test_felhasznaloTorlese()
    {
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'jogosultsag_azon' => 2
        ]);

        $response = $this->deleteJson("/api/felhasznalok/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User deleted successfully']);
    }

    /*----------------------------------- Csak az Admin fér hozzá (vége) ---------------------------------------------*/

}



#  php artisan test --filter test_updateFelhasznalo // ha csak 1 tesztet akarunk lefuttatni vagy 1 fájlt