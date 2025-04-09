<?php

namespace Database\Factories;

use App\Models\dolgozo;
use App\Models\gyakorlatihely;
use App\Models\iskola;
use Illuminate\Database\Eloquent\Factories\Factory;




class DolgozoFactory extends Factory
{
    protected $model = dolgozo::class;

    public function definition(): array
    {
        return [
            'd_azon' => $this->faker->unique()->randomNumber(5, true),

            'nev' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'szul_nev' => $this->faker->name(),
            'születesi_hely' => $this->faker->city(),
            'születesi_ido' => $this->faker->date(),
            'anyaja_neve' => $this->faker->name('female'),
            'taj_szam' => $this->faker->unique()->numerify('###-###-###'),
            'ado_szam' => $this->faker->unique()->numerify('########-#-##'),
            'gondviselo_nev' => $this->faker->name(),
            'telefonszam' => $this->faker->phoneNumber(),

            'csoport_azon' => $this->faker->randomElement(['diák', 'tanár']),
            'isk_osztály' => $this->faker->randomElement(['SZFA1', 'IRÜB2', 'IKT3', 'BGE4']),
            'akk_csoport' => $this->faker->word(),
        

            'iskola_azon' => $this->faker->randomElement(iskola::pluck('isk_azon')->toArray()), // választ a meglevők közül
            'gyakhely_azon' => $this->faker->randomElement(gyakorlatihely::pluck('gyak_azon')->toArray()),
            'megjegyzes' => $this->faker->sentence(),

        ];
    }
}
