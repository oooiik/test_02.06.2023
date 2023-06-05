<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statement>
 */
class StatementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->text,
            'number' => $this->faker->randomNumber(),
            'date' => $this->faker->dateTime(),
            'user_id' => User::query()->exists()
                ? User::query()->inRandomOrder()->first()->id
                : User::factory()->create()->id,
        ];
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['date'] = $this->faker->dateTime()->format('Y-m-d H:i:s');
        return $data;
    }
}
