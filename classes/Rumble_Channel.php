<?php

class Rumble_Channel {

	private $url;
	private $dom;
	private $video_items;

	public function __construct( $channel_url ) {
		
		$this->url  = $channel_url;
		$this->dom  = get_dom_from_url( $channel_url );

	}

	public function load() {

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

	public function get( $property ) {

		switch ( $property ) {

			case 'url':
				return $this->url;

			case 'dom':
				return $this->dom;

			case 'video_items':
				return $this->video_items;

			default:
				return "Property '$property' doesn't exist in Rumble_Channel";
		}
	}

	public function print_video_items() {

		if ( null === $this->video_items ) {

			echo 'No video items';
			return;
		}

		foreach( $this->video_items as $video_item ) {

			echo $video_item;
			echo '<br>';
		}

		return;
	}

	public function print() {

		echo '<h3>Rumble_Channel Properties</h3>';

		echo '<h4>url</h4>';
		echo print_r( $this->url );
		echo '<br><br>';

		echo '<h4>dom</h4>';
		echo print_r( $this->dom );
		echo '<br><br>';

		echo '<h4>video_items</h4>';
		$this->print_video_items();

		return;
	}
}