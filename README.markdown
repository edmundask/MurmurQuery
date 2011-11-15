
# MurmurQuery

MurmurQuery is a PHP class used for querying Murmur servers. It is based on [GT Murmur Plugin](http://www.gametracker.com/downloads/gtmurmurplugin.php), which allows us to query a Murmur server 
without having to install PHP ICE on the web server.

The response is constructed using [Channel Viewer Protocol](http://mumble.sourceforge.net/Channel_Viewer_Protocol).

# Requirements

* PHP 5
* [Murmur/Mumble](http://mumble.sourceforge.net/) 1.2.x+ installed on the server
* [GT Murmur Plugin](http://www.gametracker.com/downloads/gtmurmurplugin.php) installed on the same server on which Murmur is running

# Usage

You can check the index.php file which comes with this package to see how to use this library.

## Setup

First you need to include the library in your PHP scripts where you're going to use it.

`require_once('classes/MurmurQuery.php');`

Then you need to set the configuration. You have two ways of doing it:
* a) Pass in the parameters to the constructor.
* b) Call the `setup()` and pass in the values to it.

a) Create new instance and set the values:

`$murmur = new MurmurQuery($host, $port, $timeout, $format)`

b) Set settings array, create new MurmurQuery instance without parameters and then call the setup() method:

```
// Set the parameters
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
```

Note: when calling the setup() method, you can also set the values separately:

`$murmur->setup('127.0.0.1', 27800, 200, 'json');`

### Optional parameters

Not all parameters are necessary. To query a murmur server you only need the host if the server uses default settings. Otherwise you may need to set the port too. Timeout and format parameters are completely optional.

## Querying The Server

Querying the server is as easy as calling the query() method:

`$murmur->query();`

## Handling the response

As soon as you query the server, you have the ability to check the server's status, get full response data etc.

### Server Online Status

```
if($murmur->is_online())
{
	echo 'Server is online!';
}
else
{
	echo 'Server is offline.';
}
```

### Respnse Data

`$response = $mumur->get_status();`

This will return three pieces of information: the users, the channels and original response data.

```
print_r($response['channels']);
print_r($response['users']);
print_r($response['original']);
```

By default the library parses the returned JSON/XML data to an array and pushes extra values (channels and users). However, you can get the raw response by setting raw parameter to `true`:

`$raw_response = $murmur->get_status(true);`

Keep in mind you'll have to parse the data yourself. For example, if you're using json as the preferred format:

```
$raw_response = $murmur->get_status(true);
$data = json_decode($raw_response, true);
```

### Channels

To get the list of available channels:

`$channels = $murmur->get_channels();`

### Users

To get the list of all online users:

`$users = $murmur->get_users();`

### 

# CHANGELOG

## 0.6

* get_response() method now returns already parsed data. However, you can set get_response(true) to get the raw response.
* Added support for getting the channels and users lists separately with get_channels() and get_users() respectively.

# COPYRIGHT

Copyright (c) 2011 Edmundas Kondrašovas

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in 
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN 
THE SOFTWARE.

