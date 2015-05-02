<?php

namespace TX\Form;
use TX\HTML;
use AFM\Arr;

class CheckField
{
	private $id;
	private $value;
	
	function __construct ($id, $value = 'true')
	{
		$this->id = $id;
		$this->value = $value;
	}
	
	function render ($request, $preset, $attr = null)
	{
		$attr = Arr::ensure($attr);
		$attr['id'] = $this->id;
		$attr['name'] = $this->id;
		$attr['type'] = 'checkbox';
		if ($request->isPost())
		{
			if ($request->post($this->id) != false)
				$attr['checked'] = 'checked';
			elseif (isset($attr['checked']))
				unset($attr['checked']);
		}
		else
		{
			if ($preset != false)
				$attr['checked'] = 'checked';
			elseif (isset($attr['checked']))
				unset($attr['checked']);
		}
		$attr['value'] = $this->value;
		
		return HTML::tag('input', $attr);
	}
}