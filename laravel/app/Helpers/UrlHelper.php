<?php

namespace App\Helpers;

use App\Services\CurlService;

trait UrlHelper
{
    public static function isRumbleVideoUrl(string $url): bool
    {
        // URL is valid if:
        // - starts with "https://rumble.com/v"
        // - response status code is 200 OK
        $prefix = 'https://rumble.com/v';
        $validPrefix = (strpos($url, $prefix) === 0);
        
        if (!$validPrefix)
        {
            return false;
        }

        $response = (new CurlService($url))->get()->response();

        if (200 !== $response['statusCode'])
        {
            return false;
        }

        return true;
    }

    public static function isRumbleChannelUrl(string $url): bool
    {
        // Valid URLs:
        // https://rumble.com/c/{id}
        // https://rumble.com/c/{id}/
        // https://rumble.com/c/{id}/about
        // https://rumble.com/c/{id}/about/
        $pattern = "/^https:\/\/rumble\.com\/c\/\w+(\/about)?\/?$/";

        $isValid = (1 === preg_match($pattern, $url));

        $id = str_replace('https://rumble.com/c/', '', $url);
        $id = rtrim($id, '/about/');
        $id = rtrim($id, '/');

        return $isValid && $id !== '';
    }

    public static function removeQueryParams(string $url): string
    {
        $parsedUrl = parse_url($url);

        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'].'://' : '';
        $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

        $baseUrl = $scheme.$host.$path;

        return $baseUrl;
    }

    public static function getQueryParamValue(string $url, string $param): mixed
    {
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $params);

        if (isset($params[$param])) {
            return $params[$param];
        }

        return null;
    }

    public static function removeTrailingSlash(string $url): string
    {
        if ('/' === substr($url, -1)) {
            $url = substr($url, 0, -1);  // Remove the last character (i.e., '/')
        }

        return $url;
    }

    public static function isUrlValid(string $url): bool
    {

        $headers = @get_headers($url);

        if ($headers && false !== strpos($headers[0], '200')) {
            return true;
        }

        return false;
    }


}
