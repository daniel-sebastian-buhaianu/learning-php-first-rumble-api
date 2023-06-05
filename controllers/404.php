<?php

$response = get_error_response( 400, "Sorry, it looks like the URL you entered: $url doesn't lead anywhere." );
echo json_encode( $response );
exit;