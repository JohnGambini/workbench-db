<?php
/*--------------------------------------------------------------------------------------------
 * mysqliDatabase.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
 class mysqliDatabase extends wbDatabase {

	private $use_mysqli = true;
	public $dbh = NULL;
	
	public function connect( $dbhost, $dbuser, $dbpassword, $dbname, $dbcharset)
	{
		if ( $this->use_mysqli )
		{
			$this->dbh = @mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
			if (mysqli_connect_errno())
			{
				$this->error = "Failed to connect to MySQL: " . mysqli_connect_error();
				return false;
			}
			mysqli_set_charset($this->dbh, $dbcharset);
			$this->connected = true;
			return true;
		}
		
		return false;
	}

	public function close()
	{
		if($this->result and !is_array($this->result))
			mysqli_free_result($this->result);
		if($this->connected)
			mysqli_close($this->dbh);
		$this->connected = false;
	}

	public function query($sqlCommand)
	{
		if($this->connected)
		{
			$this->result = mysqli_query($this->dbh,$sqlCommand);
			if($this->result)
				return true;
			else
			{
				$this->error = mysqli_error($this->dbh);
				return false;
			}
		}
		
		return false;
	}

	public function query_all($sqlCommand)
	{
		if($this->connected)
		{
			$result = mysqli_query($this->dbh,$sqlCommand);
			if($result) {
				$this->result = mysqli_fetch_all($result,MYSQLI_ASSOC);
				//mysqli_free_result($result);
				return true;
			}
			else
			{
				$this->error = mysqli_error($this->dbh);
				return false;
			}
		}
	
		return false;
	}
	
	
	private function free_results()
	{
		if($this->result) mysqli_free_result($this->result);
	}
	
	public function getInsertId() {
		return mysqli_insert_id($this->dbh);
	}
	
	public function getRowCount() {
		return count($this->result);
	}
	
	public function escapeString($string) {
		return mysqli_escape_string($this->dbh, $string);
	}
}
?>