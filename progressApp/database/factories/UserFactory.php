<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login' => $this->faker->name,
            'height' => rand(1500, 2100) / 10,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('sample_password'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
