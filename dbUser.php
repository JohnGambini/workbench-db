<?php
/*--------------------------------------------------------------------------------------------
 * dbUser.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
class dbUser extends dbObject
{
	public $ID = NULL;
	public $permalink = NULL;
	public $profileId = -1;
	public $username = NULL;
	public $password = NULL;
	public $type = 0;
	public $fullName = NULL;
	public $profileImage = NULL;
	public $bio = NULL;
	public $theme = NULL;
	public $disabled = 0;
	public $addedBy = -1;
	public $groupsArray = array();
	
	/*----------------------------------------------------------------------------------------------------------------------
	 * 
	 */
	function add_user(wbDatabase $dbObj) {
	
		$sqlQuery = "insert into wb_user (profileId, username, password, type, fullName, disabled, addedBy) " .
				"values('" . $this->profileId . "','" . $this->username . "','" . $this->password .
				"','" . $this->type . "','" . $this->fullName .
				"','" . $this->disabled .
				"','" . $this->addedBy ."')";
	
		if( ! $dbObj->query($sqlQuery)) {
			$dbObj->error = "function dbUser::add_user: an error occured during mysqli_query<br><br>" . $dbObj->error . "<br><br>" . $sqlQuery;
			return false;
		}
	
		$this->ID = $dbObj->getInsertId(); 
	
		return true;
	
	}
	
	
	/*-------------------------------------------------------------------------------------------
	 * 
	 *
	 */
	public function selectHandler(wbDatabase $db) {

		$count = 0;
		
		foreach($db->result as $row) { //There will only be one row
				
			$this->ID = $row['ID'];
			$this->permalink = $row['permalink'];
			$this->fullName = $row['fullName'];
			$this->theme = $row['theme'];
			$this->type = $row['type'];
				
			$count = 1;
		}
		
		if($count != 1) {
			$db->error = "No rows returned in user query.";
			return false;	
		}
		
		return true;
		
	}

	/*-------------------------------------------------------------------------------------------------
	 *
	 */
	public function get_user(wbDatabase $db, $blob ) {
		
		$this->sqlSelect = "select ID, permalink, fullName, theme, type from vw_user where userBlob = '" . $blob . "'";
		
		if($db->query_all($this->sqlSelect)) {
			
			if($this->selectHandler($db)) {
				return true;
			}
			
			return false;
			
		}
		
		return false;
		
	}

	/*-------------------------------------------------------------------------------------------------
	 *
	 */
	public function get_user_by_id(wbDatabase $db, $id ) {
	
		$this->sqlSelect = "select ID, permalink, fullName, theme, type from vw_user where ID = '" . $id . "'";
	
		if($db->query_all($this->sqlSelect)) {
			$this->selectHandler($db);
			return true;
		}
		return false;
	}

/*-------------------------------------------------------------------------------------------------
 * 
 */
	public function get_user_groups(wbDatabase $db, $lang = "all" ) {

		$sqlquery = "";

		if($lang == "all")
			$sqlQuery = "select name from wb_grouplists, wb_usergroups where wb_grouplists.groupId = wb_usergroups.ID and wb_grouplists.userId = '" . $this->ID . "'";
		else 
			$sqlQuery = "select name from wb_grouplists, wb_usergroups where wb_grouplists.groupId = wb_usergroups.ID " .
			"and wb_grouplists.userId = '" . $this->ID . "' and wb_usergroups.lang = '" . $lang . "'";
				
		if($db->query_all($sqlQuery)) {
			foreach($db->result as $row) {
				array_push($this->groupsArray, $row['name']);
			}
			return true;
		}
		
		return false;
	}
	
/*-------------------------------------------------------------------------------------------------
 *
 */
	public function get_user_bio(wbDatabase $db, $lang ) {
	
		$sqlquery = "";
	
		$sqlQuery = "select fullName, profileImage, bio from wb_user, wb_userbios " .
		"where wb_user.ID = wb_userbios.userId and wb_user.ID = '" . $this->ID . "' and wb_userbios.lang = '" . $lang . "'";
	
		if($db->query_all($sqlQuery)) {
			foreach($db->result as $row) {
				$this->fullName = $row['fullName'];
				$this->profileImage = $row['profileImage'];
				$this->bio = $row['bio'];
			}
			
			return true;
		}
		
		$db->error = "dbUser::get_user_bio(): " . $db->error .
		"<p/>" . $sqlQuery; 
		
		return false;
	}

	/*-------------------------------------------------------------------------------------------------
	 * 
	 */
	public function groups() {
		$retString = "";
		if(count($this->groupsArray))
			for($i = 0; isset($this->groupsArray[$i]); $i++) {
				if( $i > 0 ) $retString = $retString . ", ";
				$retString = $retString . '"' . $this->groupsArray[$i] . '"';
			}
		else
			$retString = "''";
	
			return $retString;
	}
	
	/*-------------------------------------------------------------------------------------------
	 * 
	 */
	public function dump() {
	
		echo	"ID:" . $this->ID . "<br>" .
				"permalink:" . $this->permalink . "<br>" .
				"fullName: " . $this->fullName . "<br>" .
				"profileImage: " . $this->profileImage . "<br/>" .
				"bio: " . $this->bio . "<br/>" .
				"theme: " . $this->theme . "<br>" .
				"type: " . $this->type . "<br>";
		echo	"groups: ";
		for($i = 0; isset($this->groupsArray[$i]); $i++) {
			if( $i != 0 ) echo ", ";
			echo $this->groupsArray[$i];
		}
		echo	"<br/>";
	}
	
	
}

?>
