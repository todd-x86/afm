<?php

namespace TX;
use AFM\Exception;
use AFM\Arr;

class Form
{
	private $request;
	private $fields;
	private $values;
	public $data;
	
	function __construct ($request, $values = null)
	{
		$this->request = $request;
		$this->assign($values);
		$this->fields = [];
	}
	
	function __set ($field, $obj)
	{
		$this->fields[$field] = $obj;
	}
	
	function assign ($data)
	{
		if ($data === false || $data === null)
		{
			$this->values = [];
			$this->data = [];
		}
		else
		{
			$this->values = $data;
			$this->data = $data;
		}
	}
	
	function __get ($field)
	{
		return $this->getField($field);
	}
	
	function __call ($field, $attr)
	{
		return $this->getField($field, Arr::get($attr, 0, null));
	}
	
	private function getField ($field, $attr = null)
	{
		if (!isset($this->fields[$field]))
		{
			throw new Exception(sprintf('Form field "%s" is not defined', $field));
		}
		return $this->fields[$field]->render($this->request, Arr::get($this->values, $field, null), $attr);
	}
}