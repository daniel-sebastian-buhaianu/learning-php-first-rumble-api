<?php

namespace App\Rules;

use App\Models\Channel;
use App\Services\PageScrapers\Rumble\ChannelAboutPage;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InexistentRumbleChannel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rumbleChannel = new ChannelAboutPage($value);
        $rumbleChannelId = $rumbleChannel->get('rumble_id');

        if(Channel::where('rumble_id', $rumbleChannelId)->exists())
        {
            $fail('This channel already exists in the database.');
        }
    }   
}
