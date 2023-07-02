<?php

namespace App\Helpers;

trait StrHelper
{
	static public function getFirstWord(string $string): string 
    {
        $words = explode(" ", $string);
        return count($words) ? $words[0] : '';
    }

	
}