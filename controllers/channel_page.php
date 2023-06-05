<?php

$ch_url = $_GET['url'];

if ( 'about' === $ch_page ) {

	$page = new Channel_Page_About( $ch_url );

} else if ( 'videos' === $ch_page ) {

	$page = new Channel_Page_Videos( $ch_url );
}

if ( false === $page->is_page_valid() ) {

	$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $ch_url" );
	echo json_encode( $response );
	exit;
}

$response = $page->get_all();
header( 'HTTP/1.1 200 OK' );
header( 'Content-Type: application/json' );
echo json_encode( $response );
exit;