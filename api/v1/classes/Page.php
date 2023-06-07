<?php

abstract class Page {

	protected $url;
	protected $dom;
	protected $xpath;

	private $errors;

	public function __construct( $url ) {

		$this->url = $url;
		if ( ! is_url_valid( $url ) ) {

			$this->errors['url'] = 'URL is invalid or not accessible.';
			return;
		}

		$dom = get_dom_from_url( $url );
		if ( null === $dom ) {

			$this->errors['url'] = 'Invalid rumble channel page URL.';
			return;
		}

		$this->dom   = $dom;
		$this->xpath = new DOMXPath( $dom ); 
		return;
	}

	public function is_page_valid() {

		if ( empty( $this->dom ) ) return false;

		return true;
	}

	abstract public function get( $property );

	abstract public function get_all();
}