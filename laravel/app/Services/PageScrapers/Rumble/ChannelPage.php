<?php

namespace App\Services\PageScrapers\Rumble;

use App\Helpers\UrlHelper as Url;
use App\Helpers\StrHelper as Str;
use \DOMXPath;
use App\Services\PageScrapers\PageScraper;

class ChannelPage extends PageScraper
{
	protected $banner;
	protected $avatar;
	protected $title;
	protected $followers_count;
	protected $publicProperties;

	public function __construct(string $url)
	{		
		parent::__construct($url);

		$this->publicProperties = array_merge(
			$this->publicProperties, 
			['banner', 'avatar', 'title', 'followers_count']
		);

		if ($this->isScrapable())
		{
			$xpath = new DOMXPath($this->dom);

			$this->banner           = $this->extractBanner($xpath);
			$this->avatar           = $this->extractAvatar($xpath);
			$this->title            = $this->extractTitle($xpath);
			$this->followers_count  = $this->extractFollowersCount($xpath);
		}
	}

	public function convertFollowersCountToInt(): void
	{
		$word = Str::getFirstWord($this->followers_count);
        $wordLen = strlen($word);
        $lastChar = $word[$wordLen-1];

        switch ($lastChar) 
        {
            case 'M':
                $numericValue = floatval(rtrim($word, 'M'));
                $this->followers_count = intval($numericValue * 1000000);
                break;

            case 'K':
                $numericValue = floatval(rtrim($word, 'K'));
                $this->followers_count = intval($numericValue * 1000);
                break;

            default:
                $this->followers_count = intval($word);
        }
	}

	private function extractBanner(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//img[@class="channel-header--backsplash-img"]')
					->item(0);
		return $element ? $element->getAttribute('src') : null;
	}

	private function extractAvatar(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//img[@class="channel-header--thumb"]')
					->item(0);
		return $element ? $element->getAttribute('src') : null;
	}

	private function extractTitle(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//div[@class="channel-header--title"]')
					->item(0);
		return $element ? $element->firstChild->textContent : null;
	}

	private function extractFollowersCount(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//div[@class="channel-header--title"]')
					->item(0);
		return $element ? $element->lastChild->textContent : null;
	}
} 