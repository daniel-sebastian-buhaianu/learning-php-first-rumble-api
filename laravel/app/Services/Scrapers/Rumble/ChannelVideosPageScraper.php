<?php

namespace App\Services\Scrapers\Rumble;

use App\Services\Scrapers\Rumble\VideoListingScraper as VideoListing;
use App\Services\Scrapers\Rumble\ChannelPageScraper;
use App\Helpers\UrlHelper as Url;

class ChannelVideosPageScraper extends ChannelPageScraper
{
    public function __construct(string $url)
    {
        parent::__construct($url);

        if ($this->isScrapable()) {
            $this->setData($this->getChannelData());
        }
    }

    private function getChannelData(): array
    {
        $data = $this->getData();
        $data['paginator'] = $this->getChannelPaginator();
        $data['videos'] = $this->getChannelVideos();
        return $data;
    }

    private function getChannelPaginator(): array
    {
        return [
            'current' => $this->getCurrentPageIndex(),
            'next'    => $this->getNextPageIndex(),
            'last'    => $this->getLastPageIndex()
        ];
    }

    private function getChannelVideos(): array
    {
        $elements = $this->getElements('//li[@class="video-listing-entry"]');
        $videosCount = $elements->count();
        $videos = [];
        for ($i = 0; $i < $videosCount; $i++) {
            $element = $elements->item($i);
            $dom = $this->getDom();
            $html = $dom->saveHTML($element);
            $videoListing = new VideoListing($html);
            $videos[$i] = $videoListing->getData();
        }
        return $videos;
    }

    private function getCurrentPageIndex(): int
    {
        $page = Url::getQueryParamValue($this->url, 'page');
        return $page ? intval($page) : 1;
    }

    private function getNextPageIndex(): ?int
    {
        $element = $this->getFirstElement(
            '//li[@class="paginator--li paginator--li--next"]/a[1]'
        );
        return $element ? intval($element->getAttribute('aria-label'))
                        : null;
    }

    private function getLastPageIndex(): ?int
    {
        $element = $this->getFirstElement(
            '//ul[@class="paginator--ul"]/li[last()]/a[@class="paginator--link"]'
        );

        if ($element) {
            $href = $element->getAttribute('href');
            $href = 'https://rumble.com'.$href;
            $page = Url::getQueryParamValue($href, 'page');

            try {
                return intval($page);
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
