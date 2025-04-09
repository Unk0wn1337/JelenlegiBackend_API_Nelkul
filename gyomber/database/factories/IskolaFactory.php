<?php

namespace Database\Factories;

use App\Models\iskola;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Iskola>
 */
class IskolaFactory extends Factory
{
    protected $model = iskola::class;

    public function definition(): array
    {
        return [
            'nev' => $this->faker->company() . " Iskola",
            'web_oldal' => $this->faker->url(),
            'kapcsolat_tarto' => $this->faker->name(),
            'telefonszam' => $this->faker->phoneNumber(),
        ];
    }
}
