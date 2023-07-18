<?php

namespace App\Services\Scrapers;

use App\Services\CurlService;
use App\Services\Scrapers\Scraper;
use App\Helpers\HttpHelper as Http;

class PageScraper extends Scraper
{
    private $url;

    public function __construct(string $url)
    {
        $response = (new CurlService($url))->get()->response();

        if (false !== $response['data'])
        {
            parent::__construct($response['data']);
        }

        $this->url = $url;
    }

    protected function url(): ?string
    {
        return $this->url;
    }
}
