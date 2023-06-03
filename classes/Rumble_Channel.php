<?php

class Rumble_Channel {

	// unavailable in $this->get_all()
	public $class_name = 'Rumble_Channel';

	// available in $this->get_all()
	private $url;
	private $channel_id;
	private $pages_count;
	private $pages_data;

	public function __construct( $channel_url ) {

		$this->url         = $channel_url;
		$this->channel_id  = is_valid_url( $channel_url ) ? get_channel_id( $channel_url ) : null;
		$this->pages_count = get_pages_count( $this->channel_id );
		$this->pages_data  = get_pages_data( $this->channel_id, $this->pages_count ); 

	}

	public function get( $property ) {

		switch ( $property ) {

			case 'class_name':
				return $this->class_name;

			case 'url':
				return $this->url;

			case 'channel_id':
				return $this->channel_id;

			case 'pages_count':
				return $this->pages_count;

			case 'pages_data':
				return $this->pages_data;

			default:
				return "Property '$property' doesn't exist in " . $this->class_name;
		}
	}

	public function get_all() {

		return array(
			'url'         => $this->url,
			'channel_id'  => $this->channel_id,
			'pages_count' => $this->pages_count,
			'pages_data'  => $this->pages_data,
		);
	}

	public function print() {

		echo '<h3>' . $this->class_name . ' Properties</h3>';

		echo '<h4>url</h4>';
		print_r( $this->url );
		echo '<br><br>';

		echo '<h4>channel_id</h4>';
		print_r( $this->channel_id );
		echo '<br><br>';

		echo '<h4>pages_count</h4>';
		print_r( $this->pages_count );
		echo '<br><br>';

		echo '<h4>pages_data</h4>';
		print_r( $this->pages_data );
		echo '<br><br>';

		return;
	}
}

function is_valid_url( $url ) {

	$accepted_common_parts = [
		'https://rumble.com/c/',
		'https://www.rumble.com/c/',
		'rumble.com/c/',
		'www.rumble.com/c/'
	];

	// Check if the URL starts with any of the accepted common parts
	$starts_with_common_part = false;
	foreach ( $accepted_common_parts as $common_part ) {

		if ( strpos( $url, $common_part ) === 0 ) {

			$starts_with_common_part = true;
			break;
		}
	}
	if ( !$starts_with_common_part ) {

		return false;
	}

	// Get the channel ID part of the URL
	$channel_id = substr( $url, strlen( $common_part ) );

	// Check if the channel ID contains only alphanumeric characters
	if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $channel_id ) ) {

		return false;
	}

	return true;
}

function get_channel_id( $url ) {

	$accepted_common_parts = [
		'https://rumble.com/c/',
		'https://www.rumble.com/c/',
		'rumble.com/c/',
		'www.rumble.com/c/'
	];

	$common_part = '';
	foreach( $accepted_common_parts as $accepted_common_part ) {

		if ( strpos( $url, $accepted_common_part ) === 0 ) {

			$common_part = $accepted_common_part;
			break;
		}
	}
	
	$channel_id = str_replace( $common_part, '', $url );
  
	return $channel_id;
}

function get_pages_count( $channel_id ) {

	$pages_count = null;

	$url = "https://rumble.com/c/$channel_id";

	do {

		$channel_page = new Rumble_Channel_Page( $url );

		$channel_page->load_dom();
		$channel_page->load_last_page_index();

		$current_page_index = intval( $channel_page->get( 'current_page_index') ); 
		$last_page_index    = intval( $channel_page->get( 'last_page_index' ) );

		if ( $current_page_index - 1 === $last_page_index ) {

			$pages_count = $current_page_index;
		}

		$url = "https://rumble.com/c/$channel_id?page=$last_page_index";

	} while ( null === $pages_count );

	return $pages_count;
}

function get_pages_data( $channel_id, $pages_count ) {

	$pages_data  = array();

	$channel_url = "https://rumble.com/c/$channel_id";

	for ( $i = 1; $i <= $pages_count; $i++ ) {

		$page_url = $i === 1 ? $channel_url : "$channel_url?page=$i";

		$page = new Rumble_Channel_Page( $page_url );

		$page->load_dom();
		$page->load_video_items();
		$page->load_last_page_index();

		$video_items = $page->get( 'video_items' );
		$videos      = array();
		foreach( $video_items as $video_item ) {

			$video    = new Rumble_Channel_video( $video_item );
			$videos[] = $video->get_all();
		}

		$pages_data[ $page_url ] = $page->get_all();
		$pages_data[ $page_url ]['videos_data'] = $videos; 
	}

	return $pages_data;
}