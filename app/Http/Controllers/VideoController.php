<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $video_query = Video::when(
            $request->has('search'),
            function ($query) use ($request) {
                $query->where('title', 'like', "%{$request->get('search')}%");
            }
        );

        return VideoResource::collection($video_query->paginate(5));
    }

    public function store(StoreVideoRequest $request)
    {
        $video = Video::create($request->validated());

        return new VideoResource($video);
    }

    public function show(Video $video)
    {
        return new VideoResource($video);
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->validated());

        return new VideoResource($video);
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json(null, 204);
    }

    public function freeVideos()
    {
        return VideoResource::collection(Video::take(5)->paginate(5));
    }
}
