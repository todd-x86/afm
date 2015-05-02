<?php

namespace TX\Form;
use TX\HTML;
use AFM\Convert;
use AFM\Arr;

class MemoField
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
		
		if ($request->isPost())
		{
			$contents = $request->post($this->id);
		}
		else
		{
			$contents = $preset;
		}
		
		return HTML::tag('textarea', $attr, Convert::toHTML($contents));
	}
}