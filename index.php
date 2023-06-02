<?php  
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>t8tv</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
	<div id="loader-container">
		<div class="loader"></div>
	</div>

	<div>
		<label for="rumble_channel_url">Rumble Channel URL:</label>
		<input type="text" id="rumble_channel_url" name="rumble_channel_url">
		<input type="submit" id="submit" name="submit" value="Get Data">
	</div>

	<div id="result"></div>
</body>
</html>