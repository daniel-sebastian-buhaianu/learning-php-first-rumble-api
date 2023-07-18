<?php

namespace App\Services\Scrapers\Rumble;

use DOMDocument;
use App\Services\Scrapers\Scraper;

class VideoItemScraper extends Scraper
{
    public function __construct(string $html)
    {
        parent::__construct($html);

        if ($this->isScrapable()) {
            $this->setData($this->getVideoData());
        }
    }

    private function getVideoData(): array
    {
        $data = $this->getData();
        $data['thumbnail'] = $this->getThumbnail();
        $data['duration'] = $this->getDuration();
        $data['uploadedDate'] = $this->getUploadedDate();
        $data['author'] = $this->getAuthor();
        $data['likesCount'] = $this->getLikesCount();
        $data['dislikesCount'] = $this->getDislikesCount();
        $data['viewsCount'] = $this->getViewsCount();
        $data['commentsCount'] = $this->getCommentsCount();
        return $data;
    }

    private function getCommentsCount(): ?string
    {
        return $this->getTextContentOfFirstElement('//div[@class="video-counters--item video-item--comments"]');
    }

    private function getViewsCount(): ?string
    {
        return $this->getTextContentOfFirstElement('//div[@class="video-counters--item video-item--views"]');
    }

    private function getDislikesCount(): ?string
    {
        return $this->getTextContentOfFirstElement('//div[@class="rumbles-vote-vote rumbles-vote-down"]');
    }

    private function getLikesCount(): ?string
    {
        return $this->getTextContentOfFirstElement('//div[@class="rumbles-vote-vote rumbles-vote-up"]');
    }

    private function getAuthor(): ?string
    {
        return $this->getTextContentOfFirstElement('//div[@class="ellipsis-1"]');
    }

    private function getUploadedDate(): ?string
    {
        $element = $this->getFirstElement('//time[@class="video-item--meta video-item--time"]');
        return $element ? $element->getAttribute('datetime') : null;
    }

    private function getDuration(): ?string
    {
        $element = $this->getFirstElement('//span[@class="video-item--duration"]');
        return $element ? $element->getAttribute('data-value') : null;
    }

    private function getThumbnail(): ?string
    {
        return $this->getSrcOfFirstElement('//img[@class="video-item--img"]');
    }
}
