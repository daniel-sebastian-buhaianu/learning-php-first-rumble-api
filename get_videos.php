<?php

include 'includes.php';

if ( isset( $_GET['rumble_channel_url'] ) ) {

    $url = $_GET['rumble_channel_url'];

    $rumble_channel = new Rumble_Channel( $url );

    $rumble_channel->load();

    $video_items = $rumble_channel->get( 'video_items' );

    if ( empty( $video_items ) ) {

        echo json_encode( 'No video items.' );
        return;

    }

    $videos = array();
    foreach ($video_items as $video_item) {

        $html = $video_item;

        $video = new Rumble_Video( $html );

        $videos[] = $video->get_all();
    }

    echo json_encode( $videos );
    return;
}

echo json_encode( 'rumble_channel is not set' );
return;