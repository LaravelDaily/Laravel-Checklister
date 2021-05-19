<?php

namespace Database\Factories;

use App\Models\ChecklistGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChecklistGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(20),
        ];
    }
}
