<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use App\Helpers\ConversionHelper as Convert;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreChannelRequest;
use App\Services\Scrapers\Rumble\ChannelAboutPage;

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
        $data = (new ChannelAboutPage($request->input('url')))->data();

        return Channel::create(
            Convert::channelDataToDbSchema($data)
        );     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $channel = Channel::findOrFail($id);

        return $channel;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $channel = Channel::findOrFail($id);

        $url = 'https://rumble.com/c/' . $id;
        $data = (new ChannelAboutPage($url))->data();
        
        $channel->update(
            Convert::channelDataToDbSchema($data)
        );
       
        return $channel;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Channel::destroy($id))
        {
            return 'deleted';
        }

        return 'could not delete';
    }

    /**
     * Search the resource listings by title.
     */
    public function search(string $title)
    {
        return Channel::where('title', 'like', '%'.$title.'%')->get();
    }
}
