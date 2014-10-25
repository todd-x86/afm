<?php

// Response class

namespace AFM;

class Response
{
	public $status = 200;
	public $contentType = 'text/html';
	
	private $writeHeaders = true;
	
	private function printHeaders ()
	{
		header($_SERVER['SERVER_PROTOCOL'].' '.$this->status);
		header('Content-Type: '.$this->contentType);
	}
	
	function write ($content)
	{
		if ($this->writeHeaders)
		{
			$this->printHeaders();
			$this->writeHeaders = false;
		}
		print $content;
	}
}
