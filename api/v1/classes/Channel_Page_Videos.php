<?php

class Channel_Page_Videos extends Channel_Page {

	private $videos;
	private $paginator;

	private $sort_by;
	private $filters;

	private $errors;

	public function __construct( $url ) {

		parent::__construct( $url );

		$this->videos    = $this->extract_videos();
		$this->paginator = $this->extract_paginator();

		$this->sort_by                   = get_query_param( $url, 'sort' );
		$this->filters['video_date']     = get_query_param( $url, 'date' );
		$this->filters['video_duration'] = get_query_param( $url, 'duration' );
		return;
	}

	private function extract_videos() {

		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

	    $videos      = array();
	    $videos_html = array();

	    $dom   = $this->dom;
	    $xpath = $this->xpath;

	    $elements     = $xpath->query( '//li[@class="video-listing-entry"]' );
	    $videos_count = $elements->count();

	    foreach( $elements as $element ) {
	    	$videos_html[] = $dom->saveHTML( $element );
	    }

	    for ( $i = 0; $i < $videos_count; $i++ ) {

	    	$html  = $videos_html[ $i ];
	    	$dom   = dom_create_and_load( $html );
	    	$xpath = new DOMXPath( $dom );

	    	// get video url
		    $element = $xpath->query( '//a[@class="video-item--a"]' )->item(0);
		    if ( $element ) {

		        $videos[ $i ]['url'] = 'https://rumble.com' . $element->getAttribute( 'href' );

		    } else {

		    	$videos[ $i ]['url'] = null;
		    }

		    // get video title
		    $element = $xpath->query( '//h3[@class="video-item--title"]' )->item(0);
		    if ( $element ) {

		        $videos[ $i ]['title'] = $element->textContent;

		    } else {

		    	$videos[ $i ]['title'] = null;
		    }

		    // get video thumbnail
		    $element = $xpath->query( '//img[@class="video-item--img"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['thumbnail'] = $element->getAttribute('src');

		    } else {

		    	$videos[ $i ]['thumbnail'] = null;
		    }

		    // get video duration
		    $element = $xpath->query( '//span[@class="video-item--duration"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['duration'] = $element->getAttribute('data-value');

		    } else {

		    	$videos[ $i ]['duration'] = null;
		    }

		    // get video uploaded_at
		    $element = $xpath->query( '//time[@class="video-item--meta video-item--time"]' )->item(0);
		    if ( $element ) {

		        $videos[ $i ]['uploaded_at'] = array(
		            'datetime'           => $element->getAttribute( 'datetime' ),
		            'datetime_stringify' => $element->textContent
		        );

		    } else {

		    	$videos[ $i ]['uploaded_at'] = null;
		    }

		    // get video up votes
		    $element = $xpath->query( '//div[@class="rumbles-vote-vote rumbles-vote-up"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['votes']['up'] = $element->textContent;

		    } else {

		    	$videos[ $i ]['votes']['up'] = null;
		    }

		    // get video down votes
		    $element = $xpath->query( '//div[@class="rumbles-vote-vote rumbles-vote-down"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['votes']['down'] = $element->textContent;

		    } else {

		    	$videos[ $i ]['votes']['down'] = null;

		    }

		    // get video views
		    $element = $xpath->query( '//div[@class="video-counters--item video-item--views"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['counters']['views'] = $element->textContent;

		    } else {

		    	$videos[ $i ]['counters']['views'] = null;

		    }

		    //get video comments
		    $element = $xpath->query( '//div[@class="video-counters--item video-item--comments"]' )->item(0);
		    if ( $element ) {

		    	$videos[ $i ]['counters']['comments'] = $element->textContent;

		    } else {

		    	$videos[ $i ]['counters']['comments'] = null;

		    }
		}

		return $videos;
	}

	private function extract_paginator() {

		if ( null === $this->dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$paginator = array();

		$dom   = $this->dom;
		$xpath = new DOMXPath( $dom );

		// get current page index
		$query_param_page = get_query_param( $this->url, 'page' );
		$paginator['current'] = null === $query_param_page ? '1' : $query_param_page; 

		// get next page index
		$element = $xpath->query( '//li[@class="paginator--li paginator--li--next"]/a[1]' )->item(0);
		if ( $element ) {

			$paginator['next'] = $element->getAttribute( 'aria-label' );

		} else {

			$paginator['next'] = null;
		}

		// get last page index (accessible from current page)
		$element = $xpath->query( '//ul[@class="paginator--ul"]/li[last()]/a[@class="paginator--link"]' )->item(0);
		if ( $element ) {

			$href = $element->getAttribute( 'href' );
			$href = 'https://rumble.com' . $href;

			$paginator['last'] = get_query_param( $href, 'page' );

		} else {

			$paginator['last'] = null;
		}

		return $paginator;
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

			case 'videos':
				return $this->videos;

			case 'paginator':
				return $this->paginator;

			case 'sort_by':
				return $this->sort_by;

			case 'filters':
				return $this->filters;

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
			'videos'          => $this->videos,
			'paginator'		  => $this->paginator,
			'sort_by'         => $this->sort_by,
			'filters'         => $this->filters,
			'errors'          => $this->errors,
		);
	}
}