<?php

namespace AFM;

class File
{
	protected $file;
	
	function __construct ($file)
	{
		$this->file = $file;
	}
	
	function mime ()
	{
		$f = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($f, $this->file);
		finfo_close($f);
		return $mime;
	}
	
	function contents ()
	{
		return file_get_contents($this->file);
	}
}