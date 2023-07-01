<?php

namespace App\Helpers;

trait UrlHelper
{
	static public function removeQueryParams(string $url): string
	{
	    $parsedUrl = parse_url($url);

	    $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'].'://' : '';
	    $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
	    $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

	    $baseUrl = $scheme.$host.$path;

	    return $baseUrl;
	}

	static public function getQueryParamValue(string $url, string $param): mixed
	{
	    $queryString = parse_url($url, PHP_URL_QUERY);
	    parse_str($queryString, $params);

	    if (isset($params[$param])) 
	    {
	        return $params[$param];
	    }

	    return null;
	}

	static public function removeTrailingSlash(string $url): string 
    {
        if ('/' === substr($url, -1)) 
        {
            $url = substr($url, 0, -1);  // Remove the last character (i.e., '/')
        }
    
        return $url;
    }
    
	static public function isUrlValid(string $url): bool 
	{

    	$headers = @get_headers($url);

	    if ($headers && false !== strpos($headers[0], '200')) 
	    {
	        return true;
	    }

    	return false;
	}

	
}