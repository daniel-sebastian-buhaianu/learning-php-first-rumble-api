<?php

class Rumble_Channel {

	public $class_name = 'Rumble_Channel';

	private $url;
	private $id;
	private $pages_count;

	public function __construct( $channel_url ) {

		$this->url         = $channel_url;
		$this->id          = get_channel_id( $channel_url );
		$this->pages_count = get_pages_count( $channel_url ); 
	}

	public function get( $property ) {

		switch ( $property ) {

			case 'url':
				return $this->url;

			case 'id':
				return $this->id;

			case 'pages_count':
				return $this->pages_count;

			default:
				return "Property '$property' doesn't exist in " . $this->class_name;
		}
	}

	public function get_all() {

		return array(
			'url'         => $this->url,
			'id'          => $this->id,
			'pages_count' => $this->pages_count,
		);
	}

	public function print() {

		echo '<h3>' . $this->class_name . ' Properties</h3>';

		echo '<h4>url</h4>';
		print_r( $this->url );
		echo '<br><br>';

		echo '<h4>id</h4>';
		print_r( $this->id );
		echo '<br><br>';

		echo '<h4>pages_count</h4>';
		print_r( $this->pages_count );
		echo '<br><br>';

		return;
	}
}

function get_channel_id( $url ) {
	
	$common_part = 'https://rumble.com/c/';

	$channel_id = str_replace( $common_part, '', $url );
  
	return $channel_id;
}

function get_pages_count( $channel_url ) {

	$pages_count = null;

	$url = $channel_url;

	do {

		$channel_page = new Rumble_Channel_Page( $url );

		$channel_page->load_dom();
		$channel_page->load_last_page_index();

		$current_page_index = intval( $channel_page->get( 'current_page_index') ); 
		$last_page_index    = intval( $channel_page->get( 'last_page_index' ) );

		if ( $current_page_index - 1 === $last_page_index ) {

			$pages_count = $current_page_index;
		}

		$url = $channel_url . "?page=$last_page_index";

	} while ( null === $pages_count );

	return $pages_count;
}