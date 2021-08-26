<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'test_id' => $this->faker->biasedNumberBetween(1, 5),
            'question' => $this->faker->realText(),
            'correct_answer' => $this->faker->sentence()
        ];
    }
}
