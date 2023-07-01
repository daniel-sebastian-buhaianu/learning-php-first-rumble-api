<?php

namespace App\Services\PageScrapers\Rumble;

use \DOMXPath;
use App\Helpers\UrlHelper as Url;
use App\Services\PageScrapers\Rumble\ChannelPage;
use Illuminate\Support\Arr;

class ChannelAboutPage extends ChannelPage
{	
	protected $description;
	protected $joiningDate;
	protected $videosCount;
	protected $publicProperties;

	public function __construct(string $url)
	{
		// Channel About Page URL: https://rumble.com/c/{channelId}/about
		$url  = Url::removeTrailingSlash($url);
		$url  = Url::removeQueryParams($url);
		$url .= '/about';

		parent::__construct($url);

		$this->publicProperties = array_merge(
			$this->publicProperties, 
			['description', 'joiningDate', 'videosCount']
		);

		if ($this->isScrapable())
		{
			$xpath = new DOMXPath($this->dom);
			
			$this->description = $this->extractDescription($xpath);
			$this->joiningDate = $this->extractJoiningDate($xpath);
			$this->videosCount = $this->extractVideosCount($xpath);
		}
	}

	public function getPublicProperties(): array
	{
		return $this->publicProperties;
	}

	private function extractDescription(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//div[@class="channel-about--description"]/p[1]')
					->item(0);
		return $element ? $element->textContent : null;
	}

	private function extractJoiningDate(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//div[@class="channel-about-sidebar--inner"]/p[1]/text()')
					->item(0);
		return $element ? trim($element->nodeValue) : null;
	}

	private function extractVideosCount(DOMXPath $xpath): mixed
	{
		$element = $xpath
					->query('//div[@class="channel-about-sidebar--inner"]/p[2]/text()')
					->item(0);
		return $element ? trim($element->nodeValue) : null;
	}
}