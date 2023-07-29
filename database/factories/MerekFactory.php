<?php

namespace Database\Factories;

use Faker\Provider\Fakecar;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merek>
 */
class MerekFactory extends Factory
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
            'nama' => $this->faker->vehicleBrand,
        ];
    }
}
