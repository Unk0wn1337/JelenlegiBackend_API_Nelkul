<?php

namespace Database\Factories;

use App\Models\dolgozo;
use App\Models\kikuldotts;
use Illuminate\Database\Eloquent\Factories\Factory;



class KikuldottsFactory extends Factory
{

    protected $model = kikuldotts::class;

    public function definition(): array
    {
        return [
            'dolgozo_azon' => $this->faker->randomElement(dolgozo::pluck('d_azon')->toArray()), // lekerjuk az összes d_azont és egy tömbe rakjuk és abbol választunk
            'pdf_fajl_neve' => $this->faker->word() . '.pdf',   // veletlen szó generalasa
            'kuldes_datuma' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
