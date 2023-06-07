<?php

$base_path = ROOT_PATH.'/channel';

if ( $url_path === $base_path ) {

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

	$channel_url = $_GET['url'];
	$channel     = new Channel( $channel_url );
	if ( false === $channel->is_channel_valid() ) {

		$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $channel_url" );
		echo json_encode( $response );
		exit;
	}

	$channel_id    = $channel->get( 'id' );
	$channel_data  = $channel->get_all();
	$response      = array( 
		'channel' => $channel_data,
		'prev' => array( 'href' => BASE_URL ),
		'next'    => array( 
			'channel_about'  => array( 'href' => BASE_URL."/channel/$channel_id/about" ),
			'channel_videos' => array( 'href' => BASE_URL."/channel/$channel_id/videos" ),
		),
	);
	send_success_response( 200, $response );
	exit;

} else {

	$str = str_replace( $base_path, '', $url_path );
	$str = explode( '/', $str );
	$str = array_filter( $str );

	$channel_id  = $str[1];
	$channel_url = BASE_RUMBLE_CHANNEL_URL."/$channel_id";

	$str_count = count( $str );
	switch ( $str_count ) {
		case 1:
			$channel = new Channel( $channel_url );
			if ( false === $channel->is_channel_valid() ) {

				$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $channel_url" );
				echo json_encode( $response );
				exit;
			}

			$channel_data = $channel->get_all();
			$response     = array( 
				'channel' => $channel_data,
				'prev'    => array( 'href' => BASE_URL ),
				'next'    => array( 
					'channel_about'  => array( 'href' => BASE_URL."/channel/$channel_id/about" ),
					'channel_videos' => array( 'href' => BASE_URL."/channel/$channel_id/videos" ),
				),
			);
			send_success_response( 200, $response );
			exit;

		case 2:
			if ( 'about' !== $str[2]  && 'videos' !== $str[2] ) {

				$response = get_error_response( 404, 'Resource Not Found.' );
				echo json_encode( $response );
	 			exit;

			} else {

				if ( 'about' === $str[2] ) {

					$ch_page_about = new Channel_Page_About( $channel_url );
					if ( false === $ch_page_about->is_page_valid() ) {

						$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $channel_url" );
						echo json_encode( $response );
						exit;
					}

					$ch_page_data = $ch_page_about->get_all();
					$response   = array( 
						'channel' => array( 'about' => $ch_page_data ),
						'prev'    => array( 'channel' => array( 'href' => BASE_URL."/channel/$channel_id" ) ),
						'next'    => array( 'channel_videos' => array( 'href' => BASE_URL."/channel/$channel_id/videos" ) ),
					);
					send_success_response( 200, $response );
					exit;

				} else {

					$ch_page_videos = new Channel_Page_About( $channel_url );
					if ( false === $ch_page_videos->is_page_valid() ) {

						$response = get_error_response( 404, "Could not find an existent rumble channel at the url: $channel_url" );
						echo json_encode( $response );
						exit;
					}

					$ch_page_data = $ch_page_videos->get_all();
					$response     = array( 
						'channel' => array( 'videos' => $ch_page_data ),
						'prev'    => array( 'channel_about' => array( 'href' => BASE_URL."/channel/$channel_id/about" ) ),
						'next'    => null,
					);
					send_success_response( 200, $response );
					exit;
				}
			}		
		default:
			$response = get_error_response( 400, 'Invalid URL. Please adhere to the standard format: '.BASE_URL.'/channel/{channel_id}' );
			echo json_encode( $response );
		 	exit;
	}
}