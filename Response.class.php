<?php

// Response class

namespace AFM;

class Response
{
	public $status = 200;
	public $contentType = 'text/html';
	
	private $downloadAs = null;
	private $writeHeaders = true;
	
	private function printHeaders ()
	{
		header($_SERVER['SERVER_PROTOCOL'].' '.$this->status);
		header('Content-Type: '.$this->contentType);
		if ($this->downloadAs !== null)
		{
			header(sprintf('Content-Disposition: attachment; filename="%s"', $this->downloadAs));
		}
	}
	
	function download ($file)
	{
		$this->downloadAs = $file;
	}
	
	function forbidden ()
	{
		$this->status = 403;
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
