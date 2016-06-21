<?php

// Router
// NOTE: Consider using a storage mechanism passed via constructor
//       for storing URIs and callbacks in Application rather than in
//       Router

namespace AFM;

class Router
{
	private $routes = [];
	
	function store ($uri, $value)
	{
		return $this->add($uri, $value);
	}
	
	function load ($request)
	{
		if (!($values = $this->lookup($request->URI)))
		{
			return false;
		}
		
		// Set params
		$request->setParams($values[1]);
		return $values[0];
	}
	
	private function add ($route, $callable)
	{
		$this->routes[] = [$route, $callable];
	}
	
	private function lookup ($route)
	{
		foreach ($this->routes as $node)
		{
			list($matched, $params) = Routing::match($node[0], $route);
			if ($matched)
			{
				// Return [callable, params]
				return [$node[1], $params];
			}
		}
		return false;
	}
}
