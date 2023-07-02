<?php

namespace App\Http\Controllers;

use App\Services\PageScrapers\Rumble\ChannelAboutPage;
use App\Models\Channel;
use App\Http\Requests\StoreChannelRequest;
use Illuminate\Http\Request;


class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Channel::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChannelRequest $request)
    {   
        $channel = new ChannelAboutPage($request->input('url'));
        $channel->convertFollowersCountToInt();
        $channel->convertJoiningDateToMysqlDate();
        $channel->convertVideosCountToInt();

        return Channel::create($channel->getAll());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $rumbleId)
    {
        return Channel::where('rumble_id', $rumbleId)->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $rumbleId)
    {
        $channelUrl = 'https://rumble.com/c/'.$rumbleId;
        $channel = new ChannelAboutPage($channelUrl);
        $channel->convertFollowersCountToInt();
        $channel->convertJoiningDateToMysqlDate();
        $channel->convertVideosCountToInt();

        $model = Channel::where('rumble_id', $rumbleId)->firstOrFail();
        $model->update($channel->getAll());

        return $model;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Channel::destroy($id);
    }

    /**
     * Search the resource listings by title.
     */
    public function search(string $title)
    {
        return Channel::where('title', 'like', '%'.$title.'%')->get();
    }
}
