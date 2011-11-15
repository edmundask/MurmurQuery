<?php

	// Load the class
	require_once('classes/MurmurQuery.php');

	// Set the parameters.
	// Note: port, timeout and format options are not necessary if you're going to use the default values.
	$settings		=	array
	(
		'host'		=>	'31.132.2.124',
		'port'		=>	27814,
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
		echo '<h1>Status</h1>';
		echo 'The server is online!';

		// Grab the response data.
		// This includes a separate channels and users array.
		// Also, you get the original response data if you choose to parse it manually.
		$status = $murmur->get_status();

		// Get the users array
		$users = $murmur->get_users();

		// Get the channels array
		$channels = $murmur->get_channels();

		if(count($channels) > 0)
		{
			echo '<h1>Channels</h1>';
			echo '<ul>';

			foreach($channels as $channel)
			{
				echo '<li>'. $channel['name'] .'</li>';
			}

			echo '</ul>';
		}

		if(count($users) > 0)
		{
			echo '<h1>Online Users</h1>';
			echo '<ul>';

			foreach($users as $user)
			{
				echo '<li>'. $user['name'] .'</li>';
			}

			echo '</ul>';
		}

		// Display the original response data
		echo '<h1>Response</h1>';
		echo '<pre>';
		print_r($status['original']);
		echo '</pre>';
	}
	else
	{
		echo '<h1>Status</h1>';
		echo 'Sorry, the server seems to be offline.';
	}

?>