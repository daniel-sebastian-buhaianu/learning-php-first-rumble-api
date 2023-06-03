<?php

include 'includes.php';

if ( isset( $_GET['rumble_channel_url'] ) ) {

	$url = $_GET['rumble_channel_url'];

	$rumble_channel = new Rumble_Channel( $url );

	echo json_encode( $rumble_channel->get_all() );
	return;
}

