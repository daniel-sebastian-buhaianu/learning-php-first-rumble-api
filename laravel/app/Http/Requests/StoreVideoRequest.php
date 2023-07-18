<?php

namespace App\Http\Requests;

use App\Models\RumbleChannel;
use App\Rules\ResponseStatusCodeIs200;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Scrapers\Rumble\VideoPageScraper as Video;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'bail',
                'required',
                'starts_with:https://rumble.com/v',
                'active_url',
                new ResponseStatusCodeIs200,
                'unique:videos',
            ],
        ];
    }

    // /**
    //  * Get the "after" validation callables for the request.
    //  */
    // public function after(): array
    // {
    //     return [
    //         function (Validator $validator) {
    //             $video = new Video($this->input('url'));

    //             if ($this->isRumbleVideoPageValid($validator, $video)
    //                 && $this->isRumbleVideoUploaded($validator, $video)) {
    //                 $data = $video->mapAndConvertData();
    //                 $model = RumbleChannel::firstWhere('title', $data['rumble_channel_title']);
    //                 if (!$model) {
    //                     $validator->errors()->add(
    //                         'url',
    //                         'This video belongs to a channel which doesn\'t exist in the database. Please add the channel first, before trying again.'
    //                     );
    //                 } else {
    //                     $data['rumble_channel_id'] = $model->id;
    //                     unset($data['rumble_channel_title']);
    //                     session(['rumbleVideoData' => $data]);
    //                 }
    //             }
    //         }
    //     ];
    // }

    // protected function isRumbleVideoUploaded(Validator $validator, Video $video): bool
    // {
    //     $data = $video->getData();
    //     if (null === $data['uploadedDate']) {
    //         $validator->errors()->add(
    //             'url',
    //             'This video is most likely a livestream which has not started yet. Please try again once the livestream has ended.'
    //         );

    //         return false;
    //     }

    //     return true;
    // }

    // protected function isRumbleVideoPageValid(Validator $validator, Video $video): bool
    // {
    //     $data = $video->getData();
    //     if (null === $data['videoTitle']) {
    //         $validator->errors()->add(
    //             'url',
    //             'Invalid rumble video URL. (video_title is null)'
    //         );

    //         return false;
    //     }

    //     return true;
    // }
}
