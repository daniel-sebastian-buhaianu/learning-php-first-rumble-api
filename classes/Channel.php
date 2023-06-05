<?php

class Channel {

	private $url;
	private $id;
	private $about;

	private $errors;

	// Public Methods

	public function __construct( $url ) {

		$this->url = $url;
		if ( ! is_url_valid( $url ) ) {

			$this->errors['url'] = 'URL is invalid or not accessible.';
			return;
		}

		$id = $this->get_id( $url );
		if ( null === $id ) {

			$this->errors['url'] = 'Invalid rumble channel URL.';
			$this->errors['id']  = 'Could not get channel id from URL.';
			return;
		}

		$this->url   = 'https://rumble.com/c/' . $id;
		$this->id    = $id;

		$about       = new Channel_Page_About( $this->url );
		$this->about = $about->get_all(); 
		return;
	}

	public function is_channel_valid() {

		if ( empty( $this->id ) ) return false;
		
		return true;
	}

	public function get( $property ) {

		switch ( $property ) {

			case 'url':
				return $this->url;

			case 'id':
				return $this->id;

			case 'about':
				return $this->about;

			case 'errors':
				return $this->errors;

			default:
				return null;
		}
	}

	public function get_all() {

		return array(
			'url'    => $this->url,
			'id'     => $this->id,
			'about'  => $this->about,
			'errors' => $this->errors,
		);
	}

	// Private Methods

	private function get_id( $url ) {

	    $url_parts = parse_url( $url );

	    if ( ! isset( $url_parts['path'] ) ) {
	        return null;
	    }

	    $path          = $url_parts['path'];
	    $path_exploded = explode( '/', $path );
	    $channel_id    = end( $path_exploded );

	    return $channel_id;
	}
}

