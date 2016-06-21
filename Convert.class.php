<?php

namespace AFM;

class Convert
{
	public static function toString ($value)
	{
		return strval($value);
	}
	
	public static function toInteger ($value)
	{
		return intval($value);
	}
	
	public static function toFloat ($value)
	{
		return floatval($value);
	}
	
	public static function toBoolean ($value)
	{
		return filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}
	
	public static function toHTML ($value)
	{
		return htmlentities($value);
	}
}