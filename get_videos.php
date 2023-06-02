<?php

require('helper_functions.php');
require('./classes/Rumble_Channel.php');


if ( isset( $_GET['rumble_channel_url'] ) ) {

    $url = $_GET['rumble_channel_url'];

    $rumble_channel = new Rumble_Channel( $url );

    $rumble_channel->set_video_items();

    $video_items = $rumble_channel->get_video_items();

    if ( empty( $video_items ) ) {

        echo json_encode( 'No video items.' );
        return;
    }

    $videos = array();
    foreach ($video_items as $video_item) {

        $html = $video_item;

        $videos[] = array(
            'url'       => get_video_url( $html ),
            'title'     => get_video_title( $html ),
            'thumbnail' => get_video_thumbnail( $html ),
            'timestamp' => get_video_timestamp( $html ),
            'likes'     => get_video_likes( $html ),
            'dislikes'  => get_video_dislikes( $html ),
            'views'     => get_video_views( $html ),
            'comments'  => get_video_comments( $html ),
        );
    }

    echo json_encode( $videos );
    return;
}

echo json_encode( 'rumble_channel is not set' );
return;


// Helper functions

function get_video_url( $html ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $element = $xpath->query('//a[@class="video-item--a"]')->item(0);

    if ( $element ) {

        $url = 'https://rumble.com' . $element->getAttribute('href');

        return $url;
    }

    return null;
}

function get_video_title( $html ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $element = $xpath->query('//h3[@class="video-item--title"]')->item(0);

    if ( $element ) {

        return $element->textContent;
    }

    return null;
}

function get_video_thumbnail( $html ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $element = $xpath->query('//img[@class="video-item--img"]')->item(0);

    if ( $element ) {

        return $element->getAttribute('src');
    }

    return null;
}

function get_video_timestamp( $html ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $element = $xpath->query('//time[@class="video-item--meta video-item--time"]')->item(0);

    if ( $element ) {

        return array(
            'uploaded_at'           => $element->getAttribute('datetime'),
            'uploaded_at_stringify' => $element->textContent
        );
    }

    return null;
}

function get_video_likes( $html ) {

    return get_video_likes_or_dislikes( $html, 'rumbles-vote-up' );
}

function get_video_dislikes( $html ) {
    
    return get_video_likes_or_dislikes( $html, 'rumbles-vote-down' );
}

function get_video_views( $html ) {

    return get_video_views_or_comments( $html, 'video-item--views' );
}

function get_video_comments( $html ) {

    return get_video_views_or_comments( $html, 'video-item--comments' );
}



function dom_create_and_load( $html ) {

    $dom = new DOMDocument();

    libxml_use_internal_errors( true );

    $dom->loadHTML( $html );

    libxml_use_internal_errors( false );

    return $dom;
}

function get_video_likes_or_dislikes ( $html, $class ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $string  = '//div[@class="rumbles-vote-vote ' . $class . '"]';
    $element = $xpath->query( $string )->item(0);

    if ( $element ) {

        return $element->textContent;
    }

    return null;
}

function get_video_views_or_comments ( $html, $class ) {

    $dom = dom_create_and_load( $html );

    $xpath   = new DOMXPath($dom);
    $string  = '//div[@class="video-counters--item ' . $class . '"]';
    $element = $xpath->query( $string )->item(0);

    if ( $element ) {

        return $element->textContent;
    }

    return null;
}