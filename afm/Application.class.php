<?php

namespace AFM;

class Application
{
	private $router;
	private $httpResponses = [];
	private $statusCodeMap = [];
	private $runArgs = [];
	
	function __construct ()
	{
		$this->router = new Router();
		$this->initResponses();
	}
	
	private function initResponses ()
	{
		$this->httpResponses['notFound'] = [$this, 'handleNotFound'];
		$this->httpResponses['forbidden'] = [$this, 'handleForbidden'];
		$this->httpResponses['internalError'] = [$this, 'handleInternalError'];
		
		$this->statusCodeMap[404] = 'notFound';
		$this->statusCodeMap[500] = 'internalError';
		$this->statusCodeMap[403] = 'forbidden';
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
	
	function handleInternalError ($request, $response)
	{
		$response->status = 500;
		$response->write('<h1>500 Internal Server Error</h1>');
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
	
	function abort ($code = 'forbidden')
	{
		if (is_integer($code))
		{
			$code = isset($this->statusCodeMap[$code]) ? $this->statusCodeMap[$code] : 'internalError';
		}
		
		call_user_func_array($this->httpResponses[$code], $this->runArgs);
	}
	
	function run ($request)
	{
		if (!($callback = $this->router->load($request)))
		{
			$callback = $this->httpResponses['notFound'];
		}
		
		$this->runArgs = func_get_args();
		call_user_func_array($callback, $this->runArgs);
	}
}