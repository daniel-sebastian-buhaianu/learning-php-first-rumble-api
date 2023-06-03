<?php

function get_video_items( $url ) {

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );

    if ( curl_errno( $ch ) ) {

        $error_msg = curl_error( $ch );

        echo "cURL Error: " . $error_msg;

        curl_close( $ch );

        return false;
    }

    curl_close( $ch );

    $dom = new DOMDocument();
    libxml_use_internal_errors( true );
    $dom->loadHTML( $response );
    libxml_use_internal_errors( false );

    $class    = 'video-item';

    $xpath    = new DOMXPath( $dom );
    $elements = $xpath->query( "//article[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]" );

    if ( $elements ) {

        $video_items = array();

        foreach ( $elements as $element ) {

            $video_items[] = array(
                'element' => $element,
                'html'    => $dom->saveHTML($element)
            );
        }

        return $video_items;
    }

    echo "No elements of class $class found.";

    return false;
}

function add_video_items( $video_items, $url )
{
    $elements = get_video_items( $url );

    $video_items[ $url ] = ( $elements !== false ) ? $elements : null;

    return $video_items;
}

function print_video_items($video_items, $url) {

    if ( ! empty( $video_items[ $url ] ) ) {

        foreach ( $video_items[ $url ] as $item ) {

            echo $item['html'];
            echo '<br>';
        }
    } else {
        
        echo 'No results';
    }
}

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
