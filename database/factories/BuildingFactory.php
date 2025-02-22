<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = '';
        $elements = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'W', 'V', 'X', 'Y', 'Z'];
        for ($i = 0; $i < 2; $i++) {
            $state .= fake()->randomElement($elements);
        }

        return [
            'name' => fake()->company(),
            'address' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'neighborhood' => fake()->word(),
            'city' => fake()->city(),
            'state' => $state,
            'country' => fake()->country(),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => null,
        ];
    }
}
