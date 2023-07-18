<?php

namespace App\Services\Scrapers\Rumble;

use App\Helpers\ConversionHelper as Convert;
use App\Helpers\UrlHelper as Url;
use App\Services\Scrapers\Rumble\ChannelPageScraper;

class ChannelAboutPage extends ChannelPage
{
    private $data = [];

    public function __construct(string $url)
    {
        if (Url::isRumbleChannelUrl($url))
        {
            // Channel About Page URL Format: 
            // https://rumble.com/c/{channelId}/about
            $url  = Url::removeTrailingSlash($url);
            $url  = Url::removeQueryParams($url);
            $url .= '/about';

            parent::__construct($url);

            if (!empty(parent::data()))
            {
                $this->data = parent::data();

                $this->data['videosCount'] = $this->videosCount();
                $this->data['joiningDate'] = $this->joiningDate();
                $this->data['description'] = $this->description();
                $this->data['id'] = $this->id($url);
            }
        }
    }

    public function data()
    {
        return $this->data;
    }

    private function videosCount(): mixed
    {
        $videosCount = $this->scrape('//div[@class="channel-about-sidebar--inner"]/p[2]/text()')
                            ->first()
                            ->nodeValue;

        return $videosCount ? trim($videosCount) : $videosCount;
    }

    private function joiningDate(): mixed
    {
        $joiningDate = $this->scrape('//div[@class="channel-about-sidebar--inner"]/p[1]/text()')
                            ->first()
                            ->nodeValue;

        return $joiningDate ? trim($joiningDate) : $joiningDate;
    }

    private function description(): mixed
    {
        return $this->scrape('//div[@class="channel-about--description"]/p[1]')
                    ->first()
                    ->textContent;
    }

    private function id(string $channelUrl): mixed
    {
        // Pattern: https://rumble.com/c/{channel_id}/about
        $pattern = "/https:\/\/rumble.com\/c\/(\w+)\/about/";

        return preg_match($pattern, $channelUrl, $matches) ? $matches[1] : null;
    }
}
