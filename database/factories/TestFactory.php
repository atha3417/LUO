<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'test_name' => $this->faker->sentence(),
            'type_id' => 1,
            'for' => $this->faker->biasedNumberBetween(1, 3) . ',' . $this->faker->biasedNumberBetween(3, 5) . ',' . $this->faker->biasedNumberBetween(2, 4),
            'start_test' => $this->faker->dateTime('now', 'Asia/Jakarta'),
            'end_test' => $this->faker->dateTime('now', 'Asia/Jakarta'),
            'basic_point' => $this->faker->randomFloat(null, 20, 5),
            'maximal_point' => $this->faker->randomFloat(null, 100, 200),
            'duration' => $this->faker->biasedNumberBetween(15, 120),
        ];
    }
}
