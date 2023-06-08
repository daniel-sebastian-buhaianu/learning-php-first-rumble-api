<?php

require 'includes.php';

header('Access-Control-Allow-Origin: *');

$url        = get_current_page_url();
$url_parsed = parse_url( $url );
$url_path   = remove_trailing_slash( $url_parsed['path'] );
switch ( $url_path ) {

	case ROOT_PATH:
	case ROOT_PATH.'/':
		require 'controllers/home.php';
		break;

	case 0 === strpos( $url_path, ROOT_PATH.'/channel' ):
		require 'controllers/channel.php';
		break;

	default:
		require 'controllers/404.php';
		break;
}