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
}