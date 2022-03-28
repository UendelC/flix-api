<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

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
}
