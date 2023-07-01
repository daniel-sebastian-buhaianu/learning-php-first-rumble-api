<?php

namespace App\Services\PageScrapers\Rumble;

use App\Helpers\UrlHelper as Url;
use \DOMXPath;
use App\Services\PageScrapers\PageScraper;

class ChannelPage extends PageScraper
{
	protected $banner;
	protected $avatar;
	protected $title;
	protected $followersCount;
	protected $publicProperties;

	public function __construct(string $url)
	{		
		parent::__construct($url);

		$this->publicProperties = array_merge(
			$this->publicProperties, 
			['banner', 'avatar', 'title', 'followersCount']
		);

		if ($this->isScrapable())
		{
			$xpath = new DOMXPath($this->dom);

			$this->banner           = $this->extractBanner($xpath);
			$this->avatar           = $this->extractAvatar($xpath);
			$this->title            = $this->extractTitle($xpath);
			$this->followersCount   = $this->extractFollowersCount($xpath);
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