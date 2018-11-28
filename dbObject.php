<?php
/*--------------------------------------------------------------------------------------------
 * dbObject.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
abstract class dbObject
{
	public $sqlSelect = NULL;
	public $db_error = NULL;
	
	abstract protected function selectHandler(wbDatabase $db);
}
?>
