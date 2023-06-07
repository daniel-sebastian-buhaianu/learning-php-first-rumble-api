<?php

$response = array(
	'prev' => null,
	'next' => array(
		'channel' => array( 'href' => BASE_URL.'/channel?url=RUMBLE_CHANNEL_URL' ),
	),
);
send_success_response( 200, $response );
exit;