<?php

require 'includes.php';

if ( ! isset( $_GET['key'] ) ) {

	$response = get_error_response( 401, "Missing query param 'key'" );
	echo json_encode( $response );
	exit;
}

if ( ! is_api_key_valid( $_GET['key'] ) ) {

	$response = get_error_response( 401, 'Invalid API Key' );
	echo json_encode( $response );
 	exit;
}

if ( ! isset( $_GET['url'] ) ) {

	$response = get_error_response( 400, "Missing query param 'url'" );
	echo json_encode( $response );
 	exit;

}

if ( ! is_url_valid( $_GET['url'] ) ) {

	$response = get_error_response( 400, 'URL is invalid or inaccessible' );
	echo json_encode( $response );
 	exit;
}

$url = get_current_page_url();

$url_parsed = parse_url( $url );
$url_path   = remove_trailing_slash( $url_parsed['path'] );

switch ( $url_path ) {

	case ROOT_PATH . '/channel':
		require 'controllers/channel.php';
		break;

	case ROOT_PATH . '/channel/about':
		$ch_page = 'about';
		require 'controllers/channel_page.php';
		break;

	case ROOT_PATH . '/channel/videos':
		$ch_page = 'videos';
		require 'controllers/channel_page.php';
		break;

	default:
		require 'controllers/404.php';
		break;
}