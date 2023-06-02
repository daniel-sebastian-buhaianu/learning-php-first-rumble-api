$(document).ready( function() {

	$("#submit").click( function() {

		const rumbleUrl = $("#rumble_url").val();

		$("#loader-container").show();

		$.get( `get_videos.php?url=${ rumble_url }`, function( response ) {

	  		const resp = JSON.parse( response );

	  		console.log('resp', response);

			$("#loader-container").hide();
		} );

	} );
} );