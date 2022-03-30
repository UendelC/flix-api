<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        return Video::paginate();
    }

    public function show(Video $video)
    {
        return $video;
    }

    public function store(StoreVideoRequest $request)
    {
        $video = Video::create($request->validated());

        return response()->json($video, 201);
    }

    public function update(StoreVideoRequest $request, Video $video)
    {
        $video->update($request->validated());

        return response()->json($video, 200);
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json(null, 204);
    }
}
