<?php

namespace App\Services\Scrapers\Rumble;

use App\Helpers\UrlHelper as Url;
use App\Services\Scrapers\PageScraper;

class ChannelPage extends PageScraper
{
    private $data = [];

    public function __construct(string $url)
    {
        if (Url::isRumbleChannelUrl($url))
        {
            parent::__construct($url);

            if ($this->isScrapable()) 
            {
                $this->data = $this->scrapeChannelPage();
            }
        }
    }

    public function data()
    {
        return $this->data;
    }

    private function scrapeChannelPage()
    {
        return [
            'banner' => $this->banner(),
            'avatar' => $this->avatar(),
            'title' => $this->title(),
            'followersCount' => $this->followersCount()
        ];
    }

    private function followersCount(): mixed
    {
        return $this->scrape('//div[@class="channel-header--title"]')
                    ->first()
                    ->lastChild
                    ->textContent;
    }

    private function title(): mixed
    {
        return $this->scrape('//div[@class="channel-header--title"]')
                    ->first()
                    ->firstChild
                    ->textContent;
    }

    private function avatar(): mixed
    {
        return $this->scrape('//img[@class="channel-header--thumb"]')
                    ->first()
                    ->getAttribute('src');
    }

    private function banner(): mixed
    {
        return $this->scrape('//img[@class="channel-header--backsplash-img"]')
                    ->first()
                    ->getAttribute('src');
    }
}
