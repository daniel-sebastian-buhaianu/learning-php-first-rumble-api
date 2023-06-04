<?php

include 'includes.php';

if ( isset( $_GET['api_key'] ) ) {

	if ( ! is_api_key_valid( $_GET['api_key'] ) ) {

		header( 'HTTP/1.1 401 Unauthorized' );
		header( 'Content-Type: application/json' );

		$response = array(
		    'error'   => true,
		    'message' => 'Unauthorized access'
		);

		echo json_encode( $response );
   	 	exit;
	}

	if ( isset( $_GET['url'] ) ) {

		if ( ! is_rumble_channel_url_valid( $_GET['url'] ) ) {

			header( 'HTTP/1.1 404 Not Found' );
			header( 'Content-Type: application/json' );

			$response = array(
			    'error'   => true,
			    'message' => 'Could not find rumble channel.'
			);

			echo json_encode( $response );
	   	 	exit;

		}

		$rc      = new Rumble_Channel( $_GET['url'] );

		header( 'HTTP/1.1 200 OK' );
		header( 'Content-Type: application/json' );

		$response = $rc->get_all();

		echo json_encode( $response );
   	 	exit;

	} else {

		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: application/json' );

		$response = array(
		    'error'   => true,
		    'message' => 'No rumble channel url provided.'
		);

		echo json_encode( $response );
   	 	exit;
	}
} else {

	header( 'HTTP/1.1 401 Unauthorized' );
	header( 'Content-Type: application/json' );

	$response = array(
	    'error'   => true,
	    'message' => 'Unauthorized access'
	);

	echo json_encode( $response );
	exit;

}

