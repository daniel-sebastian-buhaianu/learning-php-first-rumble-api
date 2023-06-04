<?php

class Rumble_Channel_Video {

	private $class_name = 'Rumble_Channel_Video';	
	private $url;       
    private $title;     
    private $thumbnail; 
    private $uploaded_at;
    private $votes;
    private $counters;
	private $html;


    public function __construct( $html_video_item ) {

    	$dom   = dom_create_and_load( $html_video_item );
	    $xpath = new DOMXPath( $dom );

    	$this->html = $html_video_item;

    	$this->url         = $this->get_url_from_xpath( $xpath );
    	$this->title       = $this->get_title_from_xpath( $xpath );
    	$this->thumbnail   = $this->get_thumbnail_from_xpath( $xpath );
    	$this->uploaded_at = $this->get_uploaded_at_from_xpath( $xpath );
    	$this->votes       = $this->get_votes_from_xpath( $xpath );
    	$this->counters    = $this->get_counters_from_xpath( $xpath); 
    }

    // Methods

	public function get( $property ) {

		switch ( $property ) {

			case 'class_name':
				return $this->class_name;

			case 'html':
				return $this->html;

			case 'url':
				return $this->url;

			case 'title':
				return $this->title;

			case 'thumbnail':
				return $this->thumbnail;

			case 'uploaded_at':
				return $this->uploaded_at;

			case 'votes':
				return $this->votes;

			case 'counters':
				return $this->counters;

			default:
				return "Property '$property' doesn't exist in " . $this->class_name;
		}
	}

	public function get_core() {

		return array(
			'url'         => $this->url,
			'title'       => $this->title,
			'thumbnail'   => $this->thumbnail,
			'uploaded_at' => $this->uploaded_at,
			'votes'       => $this->votes,
			'counters'    => $this->counters,
		);
	}

	public function print() {

		echo '<h3>' . $this->class_name . ' Properties</h3>';

		echo '<h4>html</h4>';
		print_r( $this->html );
		echo '<br><br>';

		echo '<h4>url</h4>';
		print_r( $this->url );
		echo '<br><br>';

		echo '<h4>title</h4>';
		print_r( $this->title );
		echo '<br><br>';

		echo '<h4>thumbnail</h4>';
		print_r( $this->thumbnail );
		echo '<br><br>';

		echo '<h4>uploaded_at</h4>';
		print_r( $this->uploaded_at );
		echo '<br><br>';

		echo '<h4>votes</h4>';
		print_r( $this->votes );
		echo '<br><br>';

		echo '<h4>counters</h4>';
		print_r( $this->counters );
		echo '<br><br>';

		return;
	}

	// Helpers
	
	private function get_url_from_xpath( $xpath ) {

	    $element = $xpath->query( '//a[@class="video-item--a"]' )->item(0);

	    if ( $element ) {
	        return 'https://rumble.com' . $element->getAttribute( 'href' );
	    }

	    return null;
	}

	private function get_title_from_xpath( $xpath ) {

	    $element = $xpath->query( '//h3[@class="video-item--title"]' )->item(0);

	    if ( $element ) {

	        return $element->textContent;
	    }

	    return null;
	}

	private function get_thumbnail_from_xpath ( $xpath ) {

	    $element = $xpath->query( '//img[@class="video-item--img"]' )->item(0);

	    if ( $element ) {

	    	return $element->getAttribute('src');
	    }

	    return null;
	}

	private function get_uploaded_at_from_xpath( $xpath ) {

	    $element = $xpath->query( '//time[@class="video-item--meta video-item--time"]' )->item(0);

	    if ( $element ) {

	        return array(
	            'datetime'           => $element->getAttribute( 'datetime' ),
	            'datetime_stringify' => $element->textContent
	        );
	    }

	    return null;
	}

	private function get_votes_from_xpath( $xpath ) {

	    $votes_up   = $xpath->query( '//div[@class="rumbles-vote-vote rumbles-vote-up"]' )->item(0);
	    $votes_down = $xpath->query( '//div[@class="rumbles-vote-vote rumbles-vote-down"]' )->item(0);

	    if ( $votes_up || $votes_down ) {

	    	$votes = array();

	    	if ( $votes_up ) {

	    		$votes['up'] = $votes_up->textContent;

	    	} else {

	    		$votes['up'] = null;

	    	}

	    	if ( $votes_down ) {

	    		$votes['down'] = $votes_down->textContent;

	    	} else {

	    		$votes['down'] = null;
	    	}

	    	return $votes;
	    }

	   return null;
	}

	private function get_counters_from_xpath( $xpath ) {

	    $counters_views    = $xpath->query( '//div[@class="video-counters--item video-item--views"]' )->item(0);
	    $counters_comments = $xpath->query( '//div[@class="video-counters--item video-item--comments"]' )->item(0);

	    if ( $counters_views || $counters_comments ) {

	    	$counters = array();

	    	if ( $counters_views ) {

	    		$counters['views'] = $counters_views->textContent;

	    	} else {

	    		$counters['views'] = null;

	    	}

	    	if ( $counters_comments ) {

	    		$counters['comments'] = $counters_comments->textContent;

	    	} else {

	    		$counters['comments'] = null;
	    	}

	    	return $counters;
	    }

	   return null;
	}
}