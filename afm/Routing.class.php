<?php

namespace AFM;

class Routing
{
	private static function convertToRegex ($template)
	{
		$regex = self::filterUri($template);
		
		// Replace slashes
		$regex = str_replace('/', '\/', $regex);
		
		// Optional
		$regex = preg_replace('/\<(.+?)\>/', '([^\/]*)', $regex);
		
		// Required params
		$regex = preg_replace('/\{(.+?)\}/', '([^\/]+)', $regex);
		
		return '/^'.$regex.'$/';
	}
	
	private static function filterUri ($uri)
	{
		// Remove beginning and ending slashes
		if (substr($uri, 0, 1) == '/')
		{
			$uri = substr($uri, 1);
		}
		if (substr($uri, -1) == '/')
		{
			$uri = substr($uri, 0, -1);
		}
		
		// Remove double slashes
		while (strpos($uri, '//') !== false)
		{
			$uri = str_replace('//', '/', $uri);
		}
		
		return $uri;
	}
	
	private static function associateMatches ($matches, $template)
	{
		if (!preg_match_all('/\<(.+?)\>|\{(.+?)\}/', $template, $keywords))
		{
			return [];
		}
		$matches = array_slice($matches, 1);
		
		// Merge $keywords[1] and $keywords[2]
		$keywords = array_map(function ($a, $b) {
						return $a != '' ? $a : $b;
					}, $keywords[1], $keywords[2]);
		
		return array_combine($keywords, $matches);
	}
	
	public static function match ($template, $uri)
	{
		$pattern = self::convertToRegex($template);
		$requestUri = self::filterUri($uri);
		$success = preg_match($pattern, $requestUri, $matches);
		return [$success, $success ? self::associateMatches($matches, $template) : []];
	}
}