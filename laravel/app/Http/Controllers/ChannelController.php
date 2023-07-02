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
    public function show(Channel $channel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Channel::destroy($id);
    }
}