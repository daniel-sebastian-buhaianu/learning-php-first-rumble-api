$(document).ready( function() {

	$("#submit").click( function() {

		const rumbleChannelUrl = $("#rumble_channel_url").val();

		$("#loader-container").show();

		$.get( `get_videos.php?rumble_channel_url=${ rumbleChannelUrl }`, function( response ) {

	  		const resp = JSON.parse( response );

	  		console.log('resp', resp );

			$("#loader-container").hide();
		} );

	} );
} );