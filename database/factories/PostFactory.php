<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => [
                'en' => $this->faker->sentence(),
                'de' => $this->faker->sentence(),
                'fr' => $this->faker->sentence(),
            ],
            'content' => [
                'en' => $this->faker->paragraph(5),
                'de' => $this->faker->paragraph(5),
                'fr' => $this->faker->paragraph(5),
            ],
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }

}
