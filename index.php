<?php

include 'includes.php';

if ( isset( $_GET['api_key'] ) ) {

	if ( ! is_api_key_valid( $_GET['api_key'] ) ) {

		$response = error_response( 401, 'Unauthorized access' );

		echo json_encode( $response );
   	 	exit;
	}

	if ( isset( $_GET['url'] ) ) {

		$rc = new Rumble_Channel( $_GET['url'] );

		if ( false === $rc->get( 'is_valid' ) ) {

			$response = error_response( 404, 'Rumble Channel not found.' );
			echo json_encode( $response );
   	 		exit;
		}

		$response = $rc->get_core();
		header( 'HTTP/1.1 200 OK' );
		header( 'Content-Type: application/json' );
		echo json_encode( $response );
   	 	exit;

	} else {

		$response = error_response( 400, 'No url provided.' );

		echo json_encode( $response );
   	 	exit;
	}
} else {

	$response = error_response( 401, 'Unauthorized access' );

	echo json_encode( $response );
	exit;

}

function error_response( $status_code, $message ) {

	switch( $status_code ) {

		case 400:
			header( 'HTTP/1.1 400 Bad Request' );
			break;

		case 401:
			header( 'HTTP/1.1 401 Unauthorized' );
			break;

		default:
			header( 'HTTP/1.1 404 Not Found' );
	}

	header( 'Content-Type: application/json' );

	return array(
	    'error'   => true,
	    'message' => $message
	);
}