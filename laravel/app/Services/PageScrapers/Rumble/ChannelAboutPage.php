<?php

namespace App\Services\PageScrapers\Rumble;

use \DateTime;
use \DOMXPath;
use App\Helpers\UrlHelper as Url;
use App\Helpers\StrHelper as Str;
use App\Services\PageScrapers\Rumble\ChannelPage;
use Illuminate\Support\Arr;

class ChannelAboutPage extends ChannelPage
{	
	protected $rumble_id;
	protected $description;
	protected $joining_date;
	protected $videos_count;
	protected $publicProperties;

	public function __construct(string $url)
	{
		// Channel About Page URL: https://rumble.com/c/{channelId}/about
		$url  = Url::removeTrailingSlash($url);
		$url  = Url::removeQueryParams($url);
		$url .= '/about';

		parent::__construct($url);

		$this->rumble_id = $this->getChannelId();
		$this->publicProperties = array_merge(
			$this->publicProperties, 
			[ 'rumble_id', 'description', 'joining_date', 'videos_count']
		);

		if ($this->isScrapable())
		{
			$xpath = new DOMXPath($this->dom);
			
			$this->description = $this->extractDescription($xpath);
			$this->joining_date = $this->extractJoiningDate($xpath);
			$this->videos_count = $this->extractVideosCount($xpath);
		}
	}

	public function getPublicProperties(): array
	{
		return $this->publicProperties;
	}

	public function convertVideosCountToInt(): void
	{
		$this->videos_count = intval(Str::getFirstWord($this->videos_count));
	}

	public function convertJoiningDateToMysqlDate(): void
	{
		// Remove the "Joined " part from the joining date
        $dateString = str_replace("Joined ", "", $this->joining_date);

        // Parse the date string
        $dateTime = DateTime::createFromFormat('M d, Y', $dateString);

        // Format the date as MySQL format
        $this->joining_date = $dateTime->format('Y-m-d');
	}

	private function getChannelId()
	{
		// Pattern: https://rumble.com/c/{channel_id}/about
		$pattern = "/https:\/\/rumble.com\/c\/(\w+)\/about/";

		return preg_match($pattern, $this->url, $matches) ? $matches[1] : null;
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