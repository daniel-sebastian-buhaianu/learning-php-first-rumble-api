<?php

namespace App\Services\PageScrapers;

use \DOMDocument;
use Illuminate\Support\Arr;

abstract class PageScraper 
{
	protected $url;
	protected $dom;
	protected $publicProperties;

	public function __construct(string $url)
	{
		$this->url = $url;
		$this->dom = $this->getDom($url);
		$this->publicProperties = ['url'];
	}

	public function get(string $property): mixed
	{
		return $this->{$property} ?? null;
	}

	public function getAll(): array
	{
		$result = [];

		foreach ($this->publicProperties as $property)
		{
			$result = Arr::add($result, $property, $this->{$property});
		}

		return $result;
	}

	public function getPublicProperties(): array
	{
		return $this->publicProperties;
	}

	public function isScrapable(): bool 
	{
		return empty($this->dom) ? false : true;
	}

	protected function getDom(string $url): mixed 
	{
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $response = curl_exec($ch);

	    if (empty($response)) 
	    {
	        return null;
	    }

	    if (curl_errno($ch)) 
	    {
	        curl_close($ch);
	        return null;
	    }

	    curl_close($ch);

	    $dom = new DOMDocument();
	    libxml_use_internal_errors(true);
	    $dom->loadHTML($response);
	    libxml_use_internal_errors(false);

	    return $dom;
	}

	protected function createDomAndLoad(string $html): mixed
	{
	    $dom = new DOMDocument();
	    libxml_use_internal_errors(true);
	    $dom->loadHTML( $html );
	    libxml_use_internal_errors(false);

	    return $dom;
	}
}