<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'title' => $this->faker->sentence,
        'content' => $this->faker->paragraphs(3, true),
        'author' => $this->faker->name,
        'source' => $this->faker->company,
        'url' => $this->faker->url,
        'published_at' => $this->faker->dateTimeThisYear,
        'category' => $this->faker->randomElement(['tech', 'sports', 'health', 'business']),
        ];
    }
}
