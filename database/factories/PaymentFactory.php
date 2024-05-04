<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'renewal' => $this->faker->boolean,
            'code' => $this->faker->unique()->numberBetween(1, 100),
            'data' => $this->faker->text,
            'date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'date_start' => $this->faker->dateTimeBetween('-1 years', '+1 years'),
            'date_end' => $this->faker->dateTimeBetween('+1 years', '+2 years'),
            'status' => $this->faker->randomElement(['0','1','2'])
        ];
    }
}
