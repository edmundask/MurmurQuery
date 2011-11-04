<?php

/**
 * Murmur Query Class
 *
 * Based on GT MURMUR PLUGIN, which allows us to query a Murmur server
 * without having to install PHP ICE on the web server.
 * @link http://www.gametracker.com/downloads/gtmurmurplugin.php
 *
 * The response is constructed using Channel Viewer Protocol.
 * @link http://mumble.sourceforge.net/Channel_Viewer_Protocol
 *
 * @author		Edmundas Kondrašovas <as@edmundask.lt>
 * @license		http://www.opensource.org/licenses/MIT
 * @copyright	Copyright (c) 2011 Edmundas Kondrašovas <as@edmundask.lt>
 * @version		0.5
 *
 */

 class MurmurQuery
 {
 	/* Packets */
 	const Q_XML		= "\x78\x6D\x6C";
 	const Q_JSON	= "\x6A\x73\x6F\x6E";

 	private $users = array();		// Not in use yet
 	private $channels = array();	// Not in use yet

 	private $socket;

 	private $host;
 	private $port;
 	private $timeout;
 	private $format;

 	private $response;

 	private $status;
 	private $online = false;

 	/**
	* Constructor
	*
	* @access	public
	* @param	string			hostname 
	* @param	integer			port (optional)
	* @param	integer			timeout in miliseconds (optional)
	* @param	string			format (optional)
	* @return	void
	*/

 	public function __construct($host = '', $port = 27800, $timeout = 200, $format = 'json')
 	{
 		if(!empty($host))
 		{
 			$this->setup($host, $port, $timeout, $format);
	 		$this->query();
 		}
 	}

 	/**
	* Set the parameters
	*
	* @access	public
	* @param	string/array	hostname or settings array
	* @param	integer			port (optional)
	* @param	integer			timeout in miliseconds (optional)
	* @param	string			format (optional)
	* @return	void
	*/

 	public function setup($host, $port = 27800, $timeout = 200, $format = 'json')
 	{
 		if(is_array($host))
 		{
 			$this->host = array_key_exists('host', $host) ? $host['host'] : '';
 			$this->port = array_key_exists('port', $host) ? $host['port'] : $port;
 			$this->timeout = array_key_exists('timeout', $host) ? $host['timeout'] : $timeout;
 			$this->format = array_key_exists('format', $host) ? $host['format'] : $format;
 		}
 		else
 		{
 			$this->host = $host;
 			$this->port = $port;
 			$this->timeout = $timeout;
 			$this->format = $format;
 		}
 	}

 	/**
	* Set data format
	*
	* @access	public
	* @param	string	data format
	* @return	void
	*/

 	public function set_format($format = 'json')
 	{
 		$this->format = $format;
 	}

 	/**
	* Query the server
	*
	* @access	public
	* @return	void
	*/

 	public function query()
 	{
		$this->_connect();
		$this->_send_query($this->format);
		$this->_catch_response();

		if(!empty($this->response)) $this->online = true;

		$this->_close();
 	}

 	/**
	* Get server status
	*
	* @access	public
	* @return	string		json/xml
	*/

 	public function get_status()
 	{
 		return $this->status;
 	}

 	/**
	* Get players
	*
	* @access	public
	* @return	array
	*/

 	public function get_users()
 	{
 		// Work in progress...
 	}

 	/**
	* Check if the server is online
	*
	* @access	public
	* @return	bool
	*/

 	public function is_online()
 	{
 		return $this->online;
 	}

 	/**
	* Establish a socket connection
	*
	* @access	private
	* @return	bool
	*/

 	private function _connect()
 	{
 		// We need timeout in seconds for fsockopen()
 		$timeout = ($this->timeout < 1000) ? 1 : ceil($this->timeout / 1000);
		$this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);

		if(!$this->socket) return false;

		return true;
 	}

 	/**
	* Send query to the server
	*
	* @access	private
	* @param	string	query (should be one the constants defined)
	* @return	void
	*/

	private function _send_query($format)
	{
		$data = '';

		switch($format)
		{
			case 'json':
				$data = self::Q_JSON;
			break;

			case 'xml':
				$data = self::Q_XML;
			break;

			default:
				$data = self::Q_JSON;
			break;
		}

		if($this->socket)
		{
			fwrite($this->socket, $data);
			stream_set_timeout($this->socket, 0, $this->timeout * 1000);
		}
	}

 	/**
	* Receive response from the server
	*
	* @access	private
	* @return	void
	*/

	private function _catch_response()
	{
		if($this->socket)
		{
			while($resp = @fread($this->socket, 1024)) $this->response .= $resp;
			stream_set_timeout($this->socket, 0, $this->timeout * 1000);

			$this->status = $this->response;
		}
	}

 	/**
	* Close socket connection
	*
	* @access	private
	* @return	void
	*/

	private function _close()
	{
		if($this->socket) fclose($this->socket);

		$this->response = NULL;
		$this->data = NULL;
		$this->socket = NULL;
	}
 }

?>