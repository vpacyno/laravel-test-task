<?php

namespace Database\Factories;

use App\Models\User_details;
use Illuminate\Database\Eloquent\Factories\Factory;

class User_detailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User_details::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address
        ];
    }
}
