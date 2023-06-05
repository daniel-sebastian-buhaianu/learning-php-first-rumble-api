<?php

class Channel_Page_About extends Channel_Page {

	private $description;
	private $joining_date;
	private $videos_count;

	function __construct( $url ) {

		$url  = remove_trailing_slash( $url );
		$url .= '/about';

		parent::__construct( $url );

		$this->description  = $this->extract_description();
		$this->joining_date = $this->extract_joining_date();
		$this->videos_count = $this->extract_videos_count();
		return;
	}

	private function extract_description() {

		$xpath   = $this->xpath;
		$element = $xpath->query( '//div[@class="channel-about--description"]/p[1]' )->item(0);

		if ( $element ) return $element->textContent;

		return null;
	}

	private function extract_joining_date() {

		$xpath   = $this->xpath;
		$element = $xpath->query( '//div[@class="channel-about-sidebar--inner"]/p[1]/text()' )->item(0);

		if ( $element ) return trim( $element->nodeValue );

		return null;
	}

	private function extract_videos_count() {

		$xpath   = $this->xpath;
		$element = $xpath->query( '//div[@class="channel-about-sidebar--inner"]/p[2]/text()' )->item(0);

		if ( $element ) return trim( $element->nodeValue );

		return null;
	}

	public function get( $property ) {

		switch ( $property ) {

			case 'url':
				return $this->url;

			case 'dom':
				return $this->dom;

			case 'xpath':
				return $this->xpath;

			case 'banner':
				return $this->banner;

			case 'avatar':
				return $this->html;

			case 'title':
				return $this->title;

			case 'followers_count':
				return $this->followers_count;

			case 'description':
				return $this->description;

			case 'joining_date':
				return $this->joining_date;

			case 'videos_count':
				return $this->videos_count;

			default:
				return null;
		}
	}

	public function get_all() {

		return array(
			'url'             => $this->url,
			'banner'          => $this->banner,
			'avatar'          => $this->avatar,
			'title'           => $this->title,
			'followers_count' => $this->followers_count,
			'description'     => $this->description,
			'joining_date'    => $this->joining_date,
			'videos_count'    => $this->videos_count,
		);
	}
}