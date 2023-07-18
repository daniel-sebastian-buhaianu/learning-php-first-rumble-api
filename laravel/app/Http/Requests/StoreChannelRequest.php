<?php

namespace App\Http\Requests;

use App\Rules\ValidChannelUrlFormat;
use App\Rules\ChannelAboutPageExists;
use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
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
                'starts_with:https://rumble.com/c/',
                new ValidChannelUrlFormat,
                'unique:channels',
                'active_url',
                new ChannelAboutPageExists,
            ],
        ];
    }
}
