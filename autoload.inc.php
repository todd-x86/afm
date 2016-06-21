<?php

/**
 * Autoloader
 *
 * Establishes the standard autoloader for AppFrame Micro object instantiation -
 * [ Primary ] \ [ Directory ] \ [ Class Name ]
 */

if (file_exists('vendor/autoload.php'))
{
	require('vendor/autoload.php');
}

spl_autoload_register(function ($class) {
	if (substr($class, 0, 1) === '\\')
	{
		$class = substr($class, 1);
	}
	
	$segments = explode('\\', $class);
	
	$className = array_pop($segments);
	$firstSeg = array_shift($segments);
	
	switch ($firstSeg)
	{
		case 'App':
			$primaryDir = 'app';
			break;
		case 'AFM':
			$primaryDir = 'afm';
			break;
		default:
			$primaryDir = 'vendor/'.$firstSeg;
	}
	
	// Check primary file and an alternative filename (X.class.php vs. X.php)
	$file = $primaryDir.'/'.implode('/', $segments).'/'.$className.'.class.php';
	$altFile = $primaryDir.'/'.implode('/', $segments).'/'.$className.'.php';
	if (file_exists($file))
	{
		require($file);
		return true;
	}
	elseif (file_exists($altFile))
	{
		require($altFile);
		return true;
	}
	else
	{
		return false;
	}
});
