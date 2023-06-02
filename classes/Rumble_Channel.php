<?php

class Rumble_Channel {

	public $url;

	private $dom;
	private $video_items;

	public function __construct( $channel_url ) {

		$this->url  = $channel_url;
		$this->dom  = get_dom( $this->url );
	}

	public function set_video_items() {

		if ( null === $this->dom ) {

			$this->video_items = null;

			return;
		}

		$class    = 'video-item';

	    $xpath    = new DOMXPath( $this->dom );
	    $elements = $xpath->query( "//article[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]" );

	    $video_items = array();

	    if ( $elements ) {

	        foreach ( $elements as $element ) {

	            $video_items[] = $this->dom->saveHTML( $element );
	        }
	    }

	    $this->video_items = $video_items;

	    return;
	}

	public function get_video_items() {

		return $this->video_items;
	}

	public function print_video_items() {

		if ( empty( $this->video_items ) ) {

			echo 'Nothing to print';

			return;
		}

		foreach( $this->video_items as $video_item ) {

			echo $video_item;
			echo '<br>';
		}

		return;
	}
}

function get_dom( $url ) {

	$ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );

    if ( empty( $response ) ) {

    	return null;
    }

    if ( curl_errno( $ch ) ) {

    	curl_close( $ch );

    	return null;
    }

    curl_close( $ch );

    $dom = new DOMDocument();

    libxml_use_internal_errors( true );

    $dom->loadHTML( $response );

    libxml_use_internal_errors( false );

    return $dom;
}