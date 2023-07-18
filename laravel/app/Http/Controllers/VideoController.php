<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVideoRequest;
use App\Services\Scrapers\Rumble\VideoPage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Video::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        $url = $request->input('url');
        $vp = new VideoPage($url);
        $data = $vp->data();

        return [
            'data' => $data
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $rumbleId)
    {
        $video = RumbleVideo::where('id', $rumbleId)->firstOrFail();

        if ($request->user()->cannot('view', $video)) {
            abort(403, 'Unauthorized');
        }

        return $video;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $rumbleId)
    {
        $video = Video::where('rumble_id', $rumbleId)->firstOrFail();

        if ($request->user()->cannot('delete', $video)) {
            abort(403, 'Unauthorized');
        }

        return $video->delete();
    }
}
