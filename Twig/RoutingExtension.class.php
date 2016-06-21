<?php

namespace AFM\Twig;
use \Twig_Extension;
use \Twig_SimpleFunction;

class RoutingExtension extends Twig_Extension
{
	public function getFunctions()
	{
		return [new Twig_SimpleFunction('path', '\AFM\Path::local'),
				new Twig_SimpleFunction('url', '\AFM\Path::web')];
	}
	
	public function getName ()
	{
		return 'routing';
	}
}