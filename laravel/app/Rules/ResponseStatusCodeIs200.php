<?php

namespace App\Rules;

use Closure;
use App\Services\CurlService;
use Illuminate\Contracts\Validation\ValidationRule;

class ResponseStatusCodeIs200 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = (new CurlService($value))->get()->response();

        if (200 !== $response['statusCode']) {
            $fail('Response status code is not 200 OK.');
        }
    }
}
