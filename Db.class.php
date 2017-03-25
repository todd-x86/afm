<?php

namespace AFM;

class Db
{
	private $conn;
	
	function __construct ($host, $user, $pass, $db)
	{
		$this->conn = new \Mysqli($host, $user, $pass, $db);
	}
	
	function __destruct ()
	{
		$this->conn->close();
	}
	
	function connected ()
	{
		// TODO: Make this better
		return !empty($this->conn->connect_errno);
	}
	
	function query ($q, $params = null)
	{
		if (is_array($params))
		{
			$sql = $this->prepare($q, $params);
		}
		else
		{
			$sql = $q;
		}
		$res = $this->conn->query($sql);
		if ($res === false)
		{
			throw new Exception(sprintf('Db Exception: %s', $this->conn->error));
		}
		$results = [];
		for($j=0;$j<$res->num_rows;$j++)
		{
			$results[] = $res->fetch_assoc();
		}
		$res->close();
		return $results;
	}
	
	function queryOne ($q, $params = null)
	{
		if (is_array($params))
		{
			$sql = $this->prepare($q, $params);
		}
		else
		{
			$sql = $q;
		}
		$res = $this->conn->query($sql);
		if (!$res)
		{
			return null;
		}
		$result = $res->fetch_assoc();
		$res->close();
		return $result;
	}
	
	function insertId ()
	{
		return $this->conn->insert_id;
	}
	
	function execute ($q, $params = null)
	{
		if (is_array($params))
		{
			$sql = $this->prepare($q, $params);
		}
		else
		{
			$sql = $q;
		}
		return $this->conn->query($sql) === true;
	}
	
	private function prepare ($sql, $params)
	{
		foreach ($params as $param)
		{
			$sql = preg_replace('/\?/', $this->sqlized($param), $sql, 1);
		}
		return $sql;
	}
	
	private function sqlized ($value)
	{
		if (is_string($value))
		{
			return sprintf('\'%s\'', $this->conn->real_escape_string($value));
		}
		elseif (is_integer($value) || is_double($value))
		{
			return Convert::toString($value);
                }
                elseif ($value === NULL)
                {
                        return 'NULL';
                }
		else
		{
			// TODO: Deal with blobs later
			assert(false);
		}
	}
}
