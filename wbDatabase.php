<?php
/*--------------------------------------------------------------------------------------------
 * wbDatabase.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
abstract class wbDatabase
{
	public $result = NULL;
	public $error = "";
	protected $connected = false;
	
	
	abstract protected function connect($dbhost, $dbuser, $dbpassword, $dbname, $dbcharset);
	abstract protected function close();
	abstract protected function query_all($sqlQuery);
	abstract protected function getInsertId();
	abstract protected function getRowCount();
	abstract protected function escapeString($string);

	public function isConnected()
	{
		return $this->connected;
	}
	
}
?>