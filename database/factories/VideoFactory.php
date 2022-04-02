<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'category_id' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function withCategory()
    {
        return $this->state(
            fn () => ['category_id' => Category::factory()->create()->id]
        );
    }

}
