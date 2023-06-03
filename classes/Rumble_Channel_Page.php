<?php

class Rumble_Channel_Page {

	public $class_name = 'Rumble_Channel_Page';

	private $url;
	private $dom;
	private $video_items;
	private $current_page_index;
	private $last_page_index;

	public function __construct( $channel_url ) {
		
		$this->url                = $channel_url;
		$this->current_page_index = get_page_index( $channel_url );

		return;
	}

	public function load_dom() {

		$this->dom = get_dom_from_url( $this->url );

		return;
	}

	public function load_video_items() {

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

	public function load_last_page_index() {

		$xpath = new DOMXPath( $this->dom );

	    $elements = $xpath->query( '//a[@class="paginator--link"]' );

	   	$target = $elements->item( $elements->count() - 1 );

	   	$url = 'https://rumble.com' . $target->getAttribute( 'href' );

	   	$this->last_page_index = get_page_index( $url );

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

			case 'current_page_index':
				return $this->current_page_index;

			case 'last_page_index':
				return $this->last_page_index;

			default:
				return "Property '$property' doesn't exist in " . $this->class_name;
		}
	}

	public function get_all() {

		return array(
			'url'                => $this->url,
			'dom'                => $this->title,
			'video_items'        => $this->thumbnail,
			'current_page_index' => $this->uploaded_at,
			'last_page_index'    => $this->votes,
		);
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

		echo '<h3>' . $this->class_name . ' Properties</h3>';

		echo '<h4>url</h4>';
		print_r( $this->url );
		echo '<br><br>';

		echo '<h4>dom</h4>';
		print_r( $this->dom );
		echo '<br><br>';

		echo '<h4>video_items</h4>';
		$this->print_video_items();

		echo '<h4>current_page_index</h4>';
		print_r( $this->current_page_index );
		echo '<br><br>';

		echo '<h4>last_page_index</h4>';
		print_r( $this->last_page_index );
		echo '<br><br>';

		return;
	}
}

function get_page_index( $url ) {

	$page_index = get_query_param( $url, 'page' );

	if ( null === $page_index ) {

		return '1';
	}

	return $page_index;
}