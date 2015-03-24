<?php

namespace AFM;

class Path
{
	private static $pwd = null;
	private static $uri = null;
	
	private static function getURI ()
	{
		if (self::$uri === null)
		{
			$uri = $_SERVER['SCRIPT_NAME'];

			// If mod_rewrite is not enabled, pass everything through index.php
			if (!function_exists('apache_get_modules') || in_array('mod_rewrite', apache_get_modules()))
			{
				$uri = str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', $uri);
			}

			if (substr($uri, -1) === '/')
			{
				$uri = substr($uri, 0, -1);
			}
			
			self::$uri = $uri;
		}
		return self::$uri;
	}
	
	private static function getCwd ()
	{
		if (self::$pwd === null)
		{
			self::$pwd = getcwd();
		}
		return self::$pwd;
	}
	
	private static function buildPath ($path, $prefix = null, $separator = DIRECTORY_SEPARATOR)
	{
		if (is_array($path))
		{
			$path = self::join($path, $separator);
		}
		if ($prefix !== null)
		{
			$path = self::join([$prefix, $path], $separator);
		}
		return $path;
	}
	
	public static function local ($path)
	{
		return self::buildPath($path, self::getCwd());
	}
	
	public static function web ($path)
	{
		return self::buildPath($path, self::getURI(), '/');
	}
	
	public static function join ($path, $separator = DIRECTORY_SEPARATOR)
	{
		return implode($separator, $path);
	}
	
	public static function absolute ($path)
	{
		return self::buildPath($path);
	}
}
