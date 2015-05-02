<?php

namespace AFM;

class Type
{
	public static function isArray ($x)
	{
		if (!is_array($x)) return Exception('TypeError - value supplied is not an array');
	}
	
	public static function isString ($x)
	{
		if (!is_string($x)) return Exception('TypeError - value supplied is not a string');
	}

	public static function isInteger ($x)
	{
		if (!is_integer($x)) return Exception('TypeError - value supplied is not an integer');
	}

	public static function isFloat ($x)
	{
		if (!is_float($x)) return Exception('TypeError - value supplied is not a float');
	}

	public static function isBoolean ($x)
	{
		if (!is_bool($x)) return Exception('TypeError - value supplied is not a boolean');
	}
}
