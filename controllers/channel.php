<?php

$ch_url = $_GET['url'];

$ch = new Channel( $_GET['url'] );

if ( false === $ch->is_channel_valid() ) {

	$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $ch_url" );
	echo json_encode( $response );
	exit;
}

$response = $ch->get_all();
header( 'HTTP/1.1 200 OK' );
header( 'Content-Type: application/json' );
echo json_encode( $response );
exit;