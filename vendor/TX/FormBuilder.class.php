<?php

namespace TX;

// TODO: Handle arrays of inputs
class FormBuilder
{
	private $data;
	private $datasets;
	
	function __construct ($data = null)
	{
		$this->datasets = [];
		if ($data !== null)
		{
			$this->assign($data);
		}
	}
	
	function assign ($data)
	{
		$this->data = $data;
	}
	
	function __set ($key, $dataset)
	{
		// NOTE: Datasets should be arrays containing arrays with 'key' and 'value' keys and values
		$this->datasets[$key] = $dataset;
	}
	
	function open ($type = 'post', $upload = false)
	{
		$attr = ['method' => $type];
		if ($upload === true)
		{
			$attr['enctype'] = 'multipart/form-data';
		}
		return HTML::open('form', $attr);
	}
	
	function close ()
	{
		return HTML::close('form');
	}
	
	function combo ($id, $dataset, $attr = [])
	{
		$attr['id'] = $id;
		$attr['name'] = $id;
		$content = HTML::open('select', $attr);
		foreach ($this->datasets[$dataset] as $row)
		{
			$optAttr = ['value' => $row['key']];
			if (isset($this->data[$id]) && strcasecmp($row['key'], $this->data[$id]) == 0)
			{
				$optAttr['selected'] = 'selected';
			}
			$content .= HTML::tag('option', $optAttr, $row['value']);
		}
		$content .= HTML::close('select');
		
		return $content;
	}
	
	function memo ($id, $attr = [])
	{
		$attr['id'] = $id;
		$attr['name'] = $id;
		$contents = isset($this->data[$id]) ? $this->data[$id] : '';
		return HTML::tag('textarea', $attr, $contents);
	}
	
	function text ($id, $attr = [])
	{
		return $this->input($id, 'text', $attr);
	}
	
	function password ($id, $attr = [])
	{
		return $this->input($id, 'password', $attr);
	}
	
	function input ($id, $type, $attr = [])
	{
		$attr['type'] = $type;
		$attr['id'] = $id;
		$attr['name'] = $id;
		if (isset($this->data[$id]))
		{
			$attr['value'] = $this->data[$id];
		}
		return HTML::tag('input', $attr);
	}
	
	function checkbox ($id, $value = 'true', $attr = [])
	{
		$attr['type'] = 'checkbox';
		$attr['id'] = $id;
		$attr['name'] = $id;
		if (isset($this->data[$id]) && strcasecmp($this->data[$id], $value) == 0)
		{
			$attr['checked'] = 'checked';
		}
		return HTML::tag('input', $attr);
	}
}