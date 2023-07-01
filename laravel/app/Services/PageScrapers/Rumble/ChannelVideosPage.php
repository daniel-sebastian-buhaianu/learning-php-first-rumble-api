<?php

namespace App\Services\PageScrapers\Rumble;

use \DOMXPath;
use Illuminate\Support\Arr;
use App\Services\PageScrapers\Rumble\ChannelPage;
use App\Helpers\UrlHelper as Url;

class ChannelVideosPage extends ChannelPage
{
	protected $paginator;
	protected $videos;
	protected $publicProperties;

	public function __construct(string $url)
	{
		parent::__construct($url);

		$this->publicProperties = array_merge(
			$this->publicProperties, 
			['paginator', 'videos']
		);

		if ($this->isScrapable())
		{
			$xpath = new DOMXPath($this->dom);

			$this->paginator = $this->extractPaginator($xpath);
			$this->videos = $this->extractVideos($xpath);
		}
	}

	public function getPublicProperties(): array
	{
		return $this->publicProperties;
	}
	
	private function extractPaginator(DOMXPath $xpath)
	{
	    return [
	    	'current' => $this->extractCurrentPageIndex(),
	    	'next' => $this->extractNextPageIndex($xpath),
	    	'last' => $this->extractLastPageIndex($xpath)
	    ];
	}

	private function extractVideos(DOMXPath $xpath)
	{
	    $elements = $xpath->query('//li[@class="video-listing-entry"]');
	    $videosCount = $elements->count();

	    $videos = [];
		$videosHtml = [];

	    foreach($elements as $element) 
	    {
	    	$videosHtml[] = $this->dom->saveHTML($element);
	    }

	    for ($i = 0; $i < $videosCount; $i++)
	    {
	    	$html  = $videosHtml[$i];
	    	$dom   = $this->createDomAndLoad($html);
	    	$xpath = new DOMXPath($dom);

	    	$videos[$i]['html'] = $html;
	    	$videos[$i]['url'] = $this->extractVideoUrl($xpath);
	    	$videos[$i]['title'] = $this->extractVideoTitle($xpath);
	    	$videos[$i]['thumbnail'] = $this->extractVideoThumbnail($xpath);
	    	$videos[$i]['duration'] = $this->extractVideoDuration($xpath);
	    	$videos[$i]['uploaded_at'] = $this->extractVideoUploadedAt($xpath);
	    	$videos[$i]['likes'] = $this->extractVideoLikes($xpath);
	    	$videos[$i]['dislikes'] = $this->extractVideoDislikes($xpath);
	    	$videos[$i]['views'] = $this->extractVideoViews($xpath);
	    	$videos[$i]['comments'] = $this->extractVideoComments($xpath);
		}

		return $videos;
	}

	private function extractCurrentPageIndex(): int
	{
		$page = Url::getQueryParamValue($this->url, 'page');

		return $page ? intval($page) : 1;
	}

	private function extractNextPageIndex(DOMXPath $xpath): mixed
	{
	    $element = $xpath
	    			->query('//li[@class="paginator--li paginator--li--next"]/a[1]')
	    			->item(0);

	    return $element ? intval($element->getAttribute('aria-label')) 
	    				: null;
	}

	private function extractLastPageIndex(DOMXPath $xpath): mixed
	{
	    $element = $xpath
			    	->query('//ul[@class="paginator--ul"]/li[last()]/a[@class="paginator--link"]')
			    	->item(0);

	    if ($element) 
	    {
	        $href = $element->getAttribute('href');
	        $href = 'https://rumble.com'.$href;
	        $page = Url::getQueryParamValue($href, 'page');

	        try
	        {
	        	return intval($page);
	        }
	        catch (\Exception $e)
	        {
	        	return null;
	        }
	    }

	    return null;
	}

	private function extractVideoUrl(DOMXPath $xpath)
	{
	    $element = $xpath
	    			->query('//a[@class="video-item--a"]')
	    			->item(0);

	    return $element ? 'https://rumble.com'.$element->getAttribute('href') 
	    				: null;
	}

	private function extractVideoTitle(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//h3[@class="video-item--title"]')
					->item(0);

		return $element ? $element->textContent : null;
	}

	private function extractVideoThumbnail(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//img[@class="video-item--img"]')
					->item(0);

		return $element ? $element->getAttribute('src') : null;
	}

	private function extractVideoDuration(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//span[@class="video-item--duration"]')
					->item(0);

		return $element 
			? $element->getAttribute('data-value')
			: null;
	}

	private function extractVideoUploadedAt(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//time[@class="video-item--meta video-item--time"]')
					->item(0);

		return $element ? [
							'datetime' => $element->getAttribute('datetime'),
		        			'datetime_stringify' => $element->textContent,
		        		] : null;
	}

	private function extractVideoLikes(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//div[@class="rumbles-vote-vote rumbles-vote-up"]')
					->item(0);

		return $element ? $element->textContent : null;
	}

	private function extractVideoDislikes(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//div[@class="rumbles-vote-vote rumbles-vote-down"]')
					->item(0);

		return $element ? $element->textContent : null; 
	}

	private function extractVideoViews(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//div[@class="video-counters--item video-item--views"]')
					->item(0);

		return $element ? $element->textContent : null; 
	}

	private function extractVideoComments(DOMXPath $xpath)
	{
		$element = $xpath
					->query('//div[@class="video-counters--item video-item--comments"]')
					->item(0);

		return $element ? $element->textContent : null; 
	}
}