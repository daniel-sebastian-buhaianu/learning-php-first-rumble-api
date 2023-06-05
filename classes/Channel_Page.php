<?php

class Channel_Page extends Page {

	protected $banner;
	protected $avatar;
	protected $title;
	protected $followers_count;

	private $errors;

	public function __construct( $url ) {

		parent::__construct( $url );

		$this->banner          = $this->extract_banner();
		$this->avatar          = $this->extract_avatar();
		$this->title           = $this->extract_title();
		$this->followers_count = $this->extract_followers();
		return;
	}

	private function extract_banner() {

		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$xpath   = $this->xpath;
		$element = $xpath->query( '//img[@class="channel-header--backsplash-img"]' )->item(0);

		if ( $element ) return $element->getAttribute( 'src' );

		return null;
	}

	private function extract_avatar() {

		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$xpath   = $this->xpath;
		$element = $xpath->query( '//img[@class="channel-header--thumb"]' )->item(0);

		if ( $element ) return $element->getAttribute( 'src' );

		return null;
	}

	private function extract_title() {

		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$xpath   = $this->xpath;
		$element = $xpath->query( '//div[@class="channel-header--title"]' )->item(0);

		if ( $element ) return $element->firstChild->textContent;

		return null;
	}

	private function extract_followers() {
		
		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$xpath   = $this->xpath;
		$element = $xpath->query( '//div[@class="channel-header--title"]' )->item(0);

		if ( $element ) return $element->lastChild->textContent;

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

			case 'errors':
				return $this->errors;

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
			'errors'          => $this->errors,
		);
	}
}