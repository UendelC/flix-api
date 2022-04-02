<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllVideosRelated()
    {
        $video = Video::factory()->create(
            [
                'category_id' => Category::factory()->create()->id,
            ]
        );

        $this->getJson("api/categories/{$video->category->id}/videos")
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $video->id,
                    'title' => $video->title,
                ]
            );
    }

    public function testIndexReturnsAllCategories()
    {
        Category::factory()->count(3)->create();

        $this->getJson('/api/categories')
            ->assertSuccessful()
            ->assertJsonCount(3, 'data');
    }

    public function testCanGetACategory()
    {
        $category = Category::factory()->create();

        $this->getJson("/api/categories/{$category->id}")
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $category->id,
                    'title' => $category->title,
                ]
            );
    }

    public function testItIsPossibleToStoreACategory()
    {
        $category_payload = Category::factory()->make()->toArray();

        $this->postJson('/api/categories', $category_payload)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'title' => $category_payload['title'],
                ]
            );

        $this->assertDatabaseHas((new Category)->getTable(), $category_payload);
    }

    public function testItIsPossibleToUpdateACategory()
    {
        $category = Category::factory()->create();

        $category_payload = Category::factory()->make()->toArray();

        $this->putJson("/api/categories/{$category->id}", $category_payload)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $category->id,
                    'title' => $category_payload['title'],
                ]
            );

        $this->assertDatabaseHas((new Category)->getTable(), $category_payload);
    }

    public function testItIsPossibleToDeleteACategory()
    {
        $category = Category::factory()->create();

        $this->deleteJson("/api/categories/{$category->id}")
            ->assertSuccessful();

        $this->assertDatabaseMissing(
            (new Category)->getTable(),
            $category->toArray()
        );
    }
}
