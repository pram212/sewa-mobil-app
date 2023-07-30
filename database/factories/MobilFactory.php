<?php

namespace Database\Factories;

use App\Models\Merek;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Fakecar($this->faker));

        return [
            'merek_id' => Merek::inRandomOrder()->first()->id,
            'model' => $this->faker->vehicle,
            'plat_nomor' => $this->faker->vehicleRegistration,
            'tarif' => $this->faker->numberBetween(500000, 1000000),
            'tersedia' => true,
         ];
    }
}
