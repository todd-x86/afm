<?php

namespace TX\Form;
use TX\HTML;
use AFM\Arr;

class ComboField
{
	private $id;
	private $choices;
	
	function __construct ($id, $choices = [])
	{
		$this->id = $id;
		$this->choices = $choices;
	}
	
	private function isSelected ($request, $preset, $optValue)
	{
		if ($request->isPost())
		{
			return $request->post($this->id) == $optValue;
		}
		else
		{
			return $preset == $optValue;
		}
	}
	
	private function getOption ($request, $preset, $value)
	{
		$opt = ['value' => $value];
		if ($this->isSelected($request, $preset, $value))
			$opt['selected'] = 'selected';
		return $opt;
	}
	
	function render ($request, $preset, $attr = null)
	{
		$attr = Arr::ensure($attr);
		$attr['id'] = $this->id;
		$attr['name'] = $this->id;
		
		$options = '';
		
		if (Arr::isAssoc($this->choices))
		{
			foreach ($this->choices as $value => $label)
			{
				$opt = $this->getOption($request, $preset, $value);
				$options .= HTML::tag('option', $opt, $label);
			}
		}
		else
		{
			foreach ($this->choices as $row)
			{
				$value = Arr::getFirst($row, [0, 'value'], '');
				$label = Arr::getFirst($row, [1, 'label'], '');
				$opt = $this->getOption($request, $preset, $value);
				$options .= HTML::tag('option', $opt, $label);
			}
		}
		
		return HTML::tag('select', $attr, $options);
	}
}