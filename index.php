<?php

	// Load the class
	require_once('classes/MurmurQuery.php');

	// Set the parameters.
	// Note: port, timeout and format options are not necessary if you're going to use the default values.
	$settings		=	array
	(
		'host'		=>	'127.0.0.1',
		'port'		=>	27800,
		'timeout'	=>	200,
		'format'	=>	'json'
	);

	// Create new instance
	$murmur = new MurmurQuery();

	// Load in the settings
	$murmur->setup($settings);

	// Query the server
	$murmur->query();

	if($murmur->is_online())
	{
		echo 'The server is online!';
		echo '<br><br>';

		// Grab the response data
		$status = $murmur->get_status();

		// Since we're using JSON format, we can decode the data and have ourselves a neat array
		$server_info = json_decode($status, true);

		// Display the contents of the array
		echo '<pre>';
		print_r($server_info);
		echo '</pre>';
	}
	else
	{
		echo 'No, it seems the server is offline.';
	}

?>