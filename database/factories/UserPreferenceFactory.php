<?php

namespace Database\Factories;

use App\Models\UserPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'categories' => $this->faker->randomElements(['tech', 'sports', 'health', 'business'], 2),
            'sources' => $this->faker->randomElements(['BBC', 'CNN', 'Reuters', 'The Guardian'], 2),
            'authors' => $this->faker->randomElements(['John Doe', 'Jane Smith', 'Emily Johnson'], 2),
        ];
    }
}
