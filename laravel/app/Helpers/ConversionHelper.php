<?php

namespace App\Helpers;

use DateTime;
use App\Helpers\StrHelper as Str;

trait ConversionHelper
{
    public static function channelDataToDbSchema(array $data): array 
    {
        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'joining_date' => static::rumbleJoiningDateToMysqlDate($data['joiningDate']),
            'followers_count' => static::rumbleCountToInt($data['followersCount']),
            'videos_count' => static::videosCountToInt($data['videosCount']),
            'banner' => $data['banner'],
            'avatar' => $data['avatar'],
            'description' => $data['description'],
        ];
    }

    // "2023-07-03T20:58:21+00:00" => "July 3, 2023"
    public static function ISO8601ToString(string $ISO8601Date): string
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:sP', $ISO8601Date);
        return $dateTime->format('F j, Y');
    }

    // "June 30, 2023" => "2023-06-30 00:00:00"
    public static function rumbleUploadedDateToMysqlDatetime(string $rumbleUploadedDate): string
    {
        $datetime = DateTime::createFromFormat('F j, Y', $rumbleUploadedDate);
        return $datetime->format('Y-m-d H:i:s');
    }

    // "103.4K" => 103400
    // "103.4M" => 103400000
    // "103" => 103
    public static function rumbleCountToInt(string $rumbleCount): int
    {
        $word = Str::getFirstWord($rumbleCount);
        $wordLen = strlen($word);
        $lastChar = $word[$wordLen-1];

        switch ($lastChar) {
            case 'M':
                $numericValue = floatval(rtrim($word, 'M'));
                return intval($numericValue * 1000000);

            case 'K':
                $numericValue = floatval(rtrim($word, 'K'));
                return intval($numericValue * 1000);

            default:
                return intval($word);
        }
    }

    // "298 videos" => 298
    public static function videosCountToInt(string $videosCount): int
    {
        return intval(Str::getFirstWord($videosCount));
    }

    // "Joine June 30, 2023" => "2023-06-30"
    public static function rumbleJoiningDateToMysqlDate(string $rumbleJoiningDate): string
    {
        // Remove the "Joined " part from the joining date
        $dateString = str_replace("Joined ", "", $rumbleJoiningDate);

        // Parse the date string
        $dateTime = DateTime::createFromFormat('M d, Y', $dateString);

        // Format the date as MySQL format
        return $dateTime->format('Y-m-d');
    }
}
