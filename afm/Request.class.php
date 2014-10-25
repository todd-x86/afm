<?php

// Request class

namespace AFM;

class Request
{
	public $URL = '';
	public $URI = '';
	private $params = [];
	
	function __construct ()
	{
		$this->URI = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
		$this->URL = $this->getURL();
	}
	
	function getURL ()
	{
		$url = $this->isHTTPS() ? 'https' : 'http';
		$url .= '://';
		$url .= $this->getHostName();
		$url .= $this->URI;
		return $url;
	}
	
	function getHostName ()
	{
		return $_SERVER['HTTP_HOST'];
	}
	
	function isHTTPS ()
	{
		return !empty($_SERVER['HTTPS']);
	}
	
	function setParams ($params)
	{
		$this->params = $params;
	}
	
	function get ($id, $default = false)
	{
		return Arr::get($_GET, $id, $default);
	}
	
	function post ($id, $default = false)
	{
		return Arr::get($_POST, $id, $default);
	}
	
	function param ($id, $default = false)
	{
		return Arr::get($this->params, $id, $default);
	}
}