<?php

namespace AFM;

class Application
{
	private $router;
	private $httpResponses = [];
	
	function __construct ()
	{
		$this->router = new Router();
		$this->initResponses();
	}
	
	private function initResponses ()
	{
		$this->httpResponses['notFound'] = [$this, 'handleNotFound'];
		$this->httpResponses['forbidden'] = [$this, 'handleForbidden'];
	}
	
	function handleNotFound ($request, $response)
	{
		$response->status = 404;
		$response->write('<h1>404 Not Found</h1>');
	}
	
	function handleForbidden ($request, $response)
	{
		$response->status = 403;
		$response->write('<h1>403 Forbidden</h1>');
	}
	
	function notFound ($callback)
	{
		$this->httpResponses['notFound'] = $callback;
	}
	
	function forbidden ($callback)
	{
		$this->httpResponses['forbidden'] = $callback;
	}
	
	function route ($uri, $callback)
	{
		$this->router->store($uri, $callback);
	}
	
	function run ($request)
	{
		if (!($callback = $this->router->load($request)))
		{
			$callback = $this->httpResponses['notFound'];
		}
		call_user_func_array($callback, func_get_args());
	}
}