<?php

function dom_create_and_load( $html ) {

    $dom = new DOMDocument();

    libxml_use_internal_errors( true );

    $dom->loadHTML( $html );

    libxml_use_internal_errors( false );

    return $dom;
}

function get_dom_from_url( $url ) {

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );

    if ( empty( $response ) ) {

        return null;
    }

    if ( curl_errno( $ch ) ) {

        curl_close( $ch );

        return null;
    }

    curl_close( $ch );

    $dom = new DOMDocument();

    libxml_use_internal_errors( true );

    $dom->loadHTML( $response );

    libxml_use_internal_errors( false );

    return $dom;
}

function get_query_param( $url, $param ) {

    $query_string = parse_url( $url, PHP_URL_QUERY );

    parse_str( $query_string, $params );

    if ( isset( $params[ $param ] ) ) {

    return $params[ $param ];

    }

    return null;
}

function is_api_key_valid( $api_key ) {

    return password_verify( $api_key, MY_API_KEY );
}

function is_rumble_channel_url_valid( $url ) {

    $accepted_common_parts = [
        'https://rumble.com/c/',
        'https://www.rumble.com/c/',
        'rumble.com/c/',
        'www.rumble.com/c/'
    ];

    // Check if the URL starts with any of the accepted common parts
    $starts_with_common_part = false;
    foreach ( $accepted_common_parts as $common_part ) {

        if ( strpos( $url, $common_part ) === 0 ) {

            $starts_with_common_part = true;
            break;
        }
    }
    if ( !$starts_with_common_part ) {

        return false;
    }

    // Get the channel ID part of the URL
    $channel_id = substr( $url, strlen( $common_part ) );

    // Check if the channel ID contains only alphanumeric characters
    if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $channel_id ) ) {

        return false;
    }

    return true;
}