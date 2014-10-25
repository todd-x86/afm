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
		// Remove beginning and ending slashes
		if (substr($route, 0, 1) === '/')
		{
			$route = substr($route, 1);
		}
		if (substr($route, -1) === '/')
		{
			$route = substr($route, 0, -1);
		}
		
		$segments = array_reverse(explode('/', $route));
		$tmp = &$this->routes;
		
		$seg = array_pop($segments);
		while (count($segments) > 0)
		{
			// Match special cases
			if (preg_match('/\{\{(.+?)\}\}/', $seg, $match))
			{
				// Named wildcard
				if (!isset($tmp['@wildcard']))
				{
					$tmp['@wildcard'] = ['next' => array(), 'name' => $match[1]];
				}
				$tmp = &$tmp['@wildcard']['next'];
			}
			elseif (preg_match('/\{(.+?)\}/', $seg, $match))
			{
				// Named parameter
				if (!isset($tmp['@ident']))
				{
					$tmp['@ident'] = ['next' => array(), 'name' => $match[1]];
				}
				$tmp = &$tmp['@ident']['next'];
			}
			else
			{
				if (!isset($tmp[$seg]))
				{
					$tmp[$seg] = array();
				}
				
				// Traverse
				$tmp = &$tmp[$seg];
			}
			
			$seg = array_pop($segments);
		}
		
		if (preg_match('/\{\{(.+?)\}\}/', $seg, $match))
		{
			$tmp['@wildcard'] = ['next' => $callable, 'name' => $match[1]];
		}
		elseif (preg_match('/\{(.+?)\}/', $seg, $match))
		{
			$tmp['@ident'] = ['next' => $callable, 'name' => $match[1]];
		}
		else
		{
			$tmp[$seg] = $callable;
		}
	}
	
	// Translates a URI ($route) into a controller and action
	// False is returned when no match is found
	private function lookup ($route)
	{
		// Remove double slashes
		while (strpos($route, '//') !== false)
		{
			$route = str_replace('//', '/', $route);
		}
		
		// Remove beginning and ending slashes
		if (substr($route, 0, 1) === '/')
		{
			$route = substr($route, 1);
		}
		if (substr($route, -1) === '/')
		{
			$route = substr($route, 0, -1);
		}
		
		$params = array();
		
		$segments = array_reverse(explode('/', $route));
		
		$tmp = &$this->routes;
		
		while (count($segments) > 0)
		{
			$seg = array_pop($segments);
			
			// Match
			if (isset($tmp[$seg]))
			{
				$tmp = &$tmp[$seg];
			}
			elseif (isset($tmp['@ident']))
			{
				$params[$tmp['@ident']['name']] = $seg;
				
				$tmp = &$tmp['@ident']['next'];
			}
			elseif (isset($tmp['@wildcard']))
			{
				$params[$tmp['@wildcard']['name']] = implode('/', array_merge([$seg], array_reverse($segments)));
				$tmp = &$tmp['@wildcard']['next'];
				$segments = null;
			}
			else
			{
				return false;
			}
		}
		
		// Check for ending wildcard param
		if (!is_callable($tmp) && isset($tmp['@wildcard']))
		{
			$params[$tmp['@wildcard']['name']] = implode('/', array_reverse($segments));
			$tmp = &$tmp['@wildcard']['next'];
			$segments = null;
		}
		
		// Not a callable
		if (!is_callable($tmp))
		{
			return false;
		}
		
		$callable = $tmp;
		
		return [$callable, $params];
	}
}
