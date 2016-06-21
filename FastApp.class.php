<?php

namespace AFM;

class FastApp
{
	private $request = null;
	private $runArgs = [];
	private $called = false;
	
	function __construct ($request)
	{
		if (!($request instanceof Request))
		{
			throw new Exception('First argument must be a Request object');
		}
		
		$this->request = $request;
		$this->runArgs = func_get_args();
	}
	
	function on ($uri, $callback)
	{
		// Exit if a route has been satisfied
		if ($this->called)
		{
			return;
		}
		
		// NOTE: Last callback param is $matches
		list($success, $matches) = Routing::match($uri, $this->request->URI);
		if ($success)
		{
			call_user_func_array($callback, array_merge($this->runArgs, $matches));
			$this->called = true;
		}
	}
	
	function fallback ($callback)
	{
		if (!$this->called)
		{
			call_user_func_array($callback, $this->runArgs);
			$this->called = true;
		}
	}
}