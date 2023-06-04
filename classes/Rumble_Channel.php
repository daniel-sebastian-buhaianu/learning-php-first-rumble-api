<?php

class Rumble_Channel {

	private $class_name = 'Rumble_Channel';
	private $url;
	private $id;
	private $pages_count;
	private $pages_data;
	private $is_valid;

	public function __construct( $url ) {

		$this->is_valid = $this->is_channel_valid( $url );

		if ( false === $this->is_valid ) {
			return;
		} 

		$this->url         = $url;
		$this->id          = $this->get_id_from_url ( $url );
		$this->pages_count = $this->get_pages_count( $this->id );
		$this->pages_data  = $this->get_pages_data( $this->id, $this->pages_count );
		return; 

	}

	// Methods

	private function is_channel_valid( $url ) {

		if ( ! is_url_valid( $url ) 
			|| null === $this->get_id_from_url( $url ) ) {

			return false;
		}

		return true;
	}

	public function get( $property ) {

		switch ( $property ) {

			case 'class_name':
				return $this->class_name;

			case 'url':
				return $this->url;

			case 'id':
				return $this->id;

			case 'pages_count':
				return $this->pages_count;

			case 'pages_data':
				return $this->pages_data;

			case 'is_valid':
				return $this->is_valid;

			default:
				return "Property '$property' doesn't exist in " . $this->class_name;
		}
	}

	public function get_core() {

		return array(
			'channel_url' => $this->url,
			'channel_id'  => $this->id,
			'pages_count' => $this->pages_count,
			'pages_data'  => $this->pages_data,
		);
	}

	public function print() {

		echo '<h3>' . $this->class_name . ' Properties</h3>';

		echo '<h4>channel_url</h4>';
		print_r( $this->url );
		echo '<br><br>';

		echo '<h4>channel_id</h4>';
		print_r( $this->id );
		echo '<br><br>';

		echo '<h4>pages_count</h4>';
		print_r( $this->pages_count );
		echo '<br><br>';

		echo '<h4>pages_data</h4>';
		print_r( $this->pages_data );
		echo '<br><br>';

		return;
	}

	// Helpers

	private function get_id_from_url( $url ) {

	    $url_parts = parse_url( $url );

	    if ( ! isset( $url_parts['path'] ) ) {
	        return null;
	    }

	    $path          = $url_parts['path'];
	    $path_exploded = explode( '/', $path );
	    $channel_id    = end( $path_exploded );

	    return $channel_id;
	}

	private function get_pages_count( $channel_id ) {

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

	private function get_pages_data( $channel_id, $pages_count ) {

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

				$video    = new Rumble_Channel_Video( $video_item );
				$videos[] = $video->get_core();
			}

			$pages_data[ $page_url ] = $page->get_core();
			$pages_data[ $page_url ]['videos_data'] = $videos; 
		}

		return $pages_data;
	}
}

