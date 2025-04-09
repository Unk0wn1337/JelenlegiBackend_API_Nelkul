<?php

namespace Database\Factories;

use App\Models\gyakorlatihely;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GyakorlatiHely>
 */
class GyakorlatiHelyFactory extends Factory
{

    protected $model = gyakorlatihely::class;

    public function definition(): array
    {
        return [
            'ceg_nev' => $this->faker->company(),
            'web_oldal' => $this->faker->url(),
            'kapcsolat_tarto' => $this->faker->name(),
            'telefonszam' => $this->faker->phoneNumber(),
        ];
    }
}
