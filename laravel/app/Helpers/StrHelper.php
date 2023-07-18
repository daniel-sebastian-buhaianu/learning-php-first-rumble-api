<?php

namespace App\Helpers;

trait StrHelper
{
    public static function getFirstWord(string $string): string
    {
        $words = explode(" ", $string);
        return count($words) ? $words[0] : '';
    }
}
