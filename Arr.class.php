<?php

// Array helper

namespace AFM;

class Arr
{
	public static function ensure ($arr)
	{
		if (is_array($arr)) return $arr;
		return [];
	}
	
	public static function getFirst ($values, $keys, $default = null)
	{
		foreach ($keys as $key)
		{
			if (isset($values[$key])) return $values[$key];
		}
		return $default;
	}
	
	public static function get ($values, $key, $default = null)
	{
		if (isset($values[$key]))
		{
			return $values[$key];
		}
		else
		{
			return $default;
		}
	}
	
	public static function isAssoc ($values)
	{
		return !self::isScalar($values);
	}
	
	public static function isScalar ($values)
	{
		return array_keys($values) === range(0, count($values)-1);
	}
	
	public static function map ($values, $func)
	{
		// Because I like the OOP way better than the procedural way
		return array_map($func, $values);
	}
}