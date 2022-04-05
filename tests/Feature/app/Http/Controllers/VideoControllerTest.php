<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(User::factory()->create());
    }

    public function testIndexReturnsAllVideos()
    {
        Video::factory()->count(3)->create();

        $this->getJson('/api/videos')
            ->assertSuccessful()
            ->assertJsonCount(3, 'data');
    }

    public function testCanGetAVideo()
    {
        $video = Video::factory()->create();

        $this->getJson('/api/videos/' . $video->id)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'url' => $video->url,
                ]
            );
    }

    public function testItIsPossibleToStoreAVideo()
    {
        $video_payload = Video::factory()->make()->toArray();

        $this->postJson('/api/videos', $video_payload)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'title' => $video_payload['title'],
                    'description' => $video_payload['description'],
                    'url' => $video_payload['url'],
                ]
            );

        $this->assertDatabaseHas((new Video)->getTable(), $video_payload);
    }

    public function testItIsPossibleToUpdateAVideo()
    {
        $video = Video::factory()
            ->withCategory()
            ->create();

        $video_payload = Video::factory()
            ->make(['category_id' => $video->category_id])
            ->toArray();

        $this->putJson('/api/videos/' . $video->id, $video_payload)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $video->id,
                    'title' => $video_payload['title'],
                    'description' => $video_payload['description'],
                    'url' => $video_payload['url'],
                ]
            );

        $this->assertDatabaseHas(
            (new Video)->getTable(),
            $video_payload + ['id' => $video->id]
        );
    }

    public function testItIsPossibleToDeleteAVideo()
    {
        $video = Video::factory()->create();

        $this->deleteJson('/api/videos/' . $video->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing((new Video)->getTable(), ['id' => $video->id]);
    }

    public function testItIsPossibleToSearchVideos()
    {
        $video = Video::factory()->create();

        $this->getJson('/api/videos?search=' . $video->title)
            ->assertSuccessful()
            ->assertJsonFragment(
                [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'url' => $video->url,
                ]
            );
    }

    public function testAnyoneCanGetFiveFreeVideos()
    {
        Video::factory()->count(5)->create();

        $this->getJson('/api/videos/free')
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    }
}
