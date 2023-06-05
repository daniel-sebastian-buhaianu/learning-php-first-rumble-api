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

function is_url_valid( $url ) {

    $headers = @get_headers( $url );

    if ( $headers && false !== strpos( $headers[0], '200' ) ) {
        return true;
    }

    return false;
}

function get_pages_data( $channel_id, $pages_count ) {

    $pages_data  = array();

    $channel_url = "https://rumble.com/c/$channel_id";

    for ( $i = 1; $i <= $pages_count; $i++ ) {

        $page_url = $i === 1 ? $channel_url : "$channel_url?page=$i";

        $page = new Rumble_Channel_Page( $page_url );

        $page->load_dom();
        $page->load_video_items();
        $page->load_last_page_index();

        $video_items = $page->get( 'video_items' );
        $videos      = array();
        foreach( $video_items as $video_item ) {

            $video    = new Rumble_Channel_Video( $video_item );
            $videos[] = $video->get_core();
        }

        $pages_data[ $page_url ] = $page->get_core();
        $pages_data[ $page_url ]['videos_data'] = $videos; 
    }

    return $pages_data;
}

function extract_pages_count() {

    $count = null;

    $channel_url = $this->url;

    $url = $channel_url;

    do {

        $channel_page = new Rumble_Channel_Page( $url );

        $channel_page->load_dom();
        $channel_page->load_last_page_index();

        $current_page_index = intval( $channel_page->get( 'current_page_index') ); 
        $last_page_index    = intval( $channel_page->get( 'last_page_index' ) );

        if ( $current_page_index - 1 === $last_page_index ) {

            $count = $current_page_index;
        }

        $url = "$channel_url?page=$last_page_index";

    } while ( null === $count );

    return $count;
}

function remove_trailing_slash( $url ) {

    if ( substr( $url, -1 ) === '/' ) {

        $url = substr( $url, 0, -1 );  // Remove the last character (i.e., '/')
    }
    
    return $url;
}
