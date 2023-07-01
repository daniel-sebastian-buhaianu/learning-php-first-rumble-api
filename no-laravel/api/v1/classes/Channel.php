<?php

class Channel {

	private $url;
	private $id;

	private $errors;

	// Public Methods

	public function __construct( $url ) {

		$this->url = $url;
		
		if ( ! is_url_valid( $url ) ) {

			$this->errors['url'] = 'Rumble Channel URL is invalid.';
			return;
		}

		$id = get_channel_id( $url );

		if ( null === $id ) {

			$this->errors['id']  = 'Could not get channel id from URL. Check if you have entered a valid Rumble Channel URL.';
			return;
		}

		$this->url   = 'https://rumble.com/c/' . $id;
		$this->id    = $id;
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
			'errors' => $this->errors,
		);
	}
}

