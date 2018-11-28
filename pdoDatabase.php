<?php
/*--------------------------------------------------------------------------------------------
 * pdoDatabase.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
 class pdoDatabase extends wbDatabase {

	public $pdo;
	
	public function connect($dbhost, $dbuser, $dbpassword, $dbname, $dbcharset) {

		$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$dbcharset";
		$opt = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
				PDO::ATTR_PERSISTENT => false,
		];
		
		try{
			$this->pdo = new PDO($dsn, $dbuser, $dbpassword, $opt);
		} catch(PDOException $e) {
			$this->error = $e->getMessage();
			/*
			echo $e->getCode();
			echo "<br/>";
			echo $e->getFile();
			echo "<br/>";
			echo $e->getLine();
			echo "<br/>";
			echo $e->getMessage();
			echo "<br/>";
			echo $e->getTraceAsString();
			echo "<br/>";
			*/
			return false;
		}
		
		return true;
	}
	
	public function close() {
		$this->pdo = null;	
	}
	
	public function query_all($sqlQuery) {
		try {
			$this->result = $this->pdo->prepare($sqlQuery);
			$this->result->execute();
			return true;
		} catch (PDOException $e) {
			$this->db_error = $e->getMessage();
			return false;
			//echo $e->getCode();
			//echo "<br/>";
			//echo $e->getFile();
			//echo "<br/>";
			//echo $e->getLine();
			//echo "<br/>";
			//echo $e->getMessage();
			//echo "<br/>";
			//echo $e->getTraceAsString();
			//echo "<br/>";
		}
		
		
	}
	
	public function getInsertId() {
		return $pdo->lastInsertId();
	}
	
	public function getRowCount() {
		return $this->result->rowCount();
	}
	
	public function escapeString($string) {
		return $string;
	}
	
}
?>
