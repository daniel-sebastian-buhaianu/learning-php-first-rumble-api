<?php

namespace App\Rules;

use Closure;
use App\Services\CurlService;
use App\Helpers\UrlHelper as Url;
use Illuminate\Contracts\Validation\ValidationRule;

class ChannelAboutPageExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $url = $value;
        $url = Url::removeTrailingSlash($url) . '/about';

        $response = (new CurlService($url))->get()->response();

        if (200 !== $response['statusCode']) {
            $fail('Invalid rumble channel URL. (ChannelAboutPageExists)');
        }
    }
}
