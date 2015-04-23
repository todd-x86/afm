<?php

// Array helper

namespace AFM;

class Arr
{
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
	
	public static function map ($values, $func)
	{
		// Because I like the OOP way better than the procedural way
		return array_map($func, $values);
	}
}