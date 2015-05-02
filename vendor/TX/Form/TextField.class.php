<?php

namespace TX\Form;
use TX\HTML;
use AFM\Arr;

class TextField
{
	private $id;
	
	function __construct ($id)
	{
		$this->id = $id;
	}
	
	function render ($request, $preset, $attr = null)
	{
		$attr = Arr::ensure($attr);
		$attr['id'] = $this->id;
		$attr['name'] = $this->id;
		$attr['type'] = 'text';
		
		if ($request->isPost())
		{
			$attr['value'] = $request->post($this->id);
		}
		elseif ($preset != '')
		{
			$attr['value'] = $preset;
		}
		
		return HTML::tag('input', $attr);
	}
}