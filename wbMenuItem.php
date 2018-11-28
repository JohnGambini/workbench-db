<?php
/*--------------------------------------------------------------------------------------------
 * wbMenuItem.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
 class wbMenuItem {
	public $ID = NULL;
	public $menuId = NULL;
	public $menuType = NULL;
	public $sequence = NULL;
	public $contentId = NULL;
	
	function add($dbObj) {
		
		$sqlQuery = "insert into wb_menuitems ( menuId, menuType, sequence, contentId ) ".
				"values(" . $this->menuId .
				"," . $this->menuType . 
				"," . $this->sequence .
				"," . $this->contentId . ")";
		
		if( ! $dbObj->query($sqlQuery)){
			$dbObj->error =  "wbMenuItem->add: an error occurred during mysqli_query<br><br>" . $dbObj->error .
			"<br><br>" . $sqlQuery;
			return false;
		}
		
		$this->ID = mysqli_insert_id($dbObj->dbh);
		
		return true;
	}
}