<?php
/*--------------------------------------------------------------------------------------------
 * wb_database_updates.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
 /*--------------------------------------------------------------------------------------
 * database_updates($dbObj)
 */
function database_updates($dbObj, $userObj) {
	
mysqli_begin_transaction($dbObj->dbh, MYSQLI_TRANS_START_READ_WRITE);

/* add user group record */
if( isset($_POST['addGroup'])) {
	if(!add_usergroup($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* add user group record */
if( isset($_POST['addToGroup'])) {
	if(!add_to_group($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* delete grouplist records */
if( isset($_POST['deleteGroupList'])) {
	if(!delete_from_grouplist($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* delete user group record */
if( isset($_POST['deleteGroup'])) {
	if(!delete_from_usergroup($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* add a content record */
if( isset($_POST['addContent'])) {
	if( !add_content($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* update a content record */
if( isset($_POST['updateContent'])) {
	if( !update_content($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* delete a content record and associated menu items */
if( isset($_POST['deleteContent'])) {
	if( !delete_content($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* add a menu record to the content table */
if( isset($_POST['addMenu'])) {
	if( !add_menu($dbObj,$userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* delete a menu record from the content table */
if( isset($_POST['deleteMenu'])) {
	if( !delete_menu($dbObj,$userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* add a menu item record */
if( isset($_POST['addMenuItem'])) {
	if( !add_menu_item($dbObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}
	
if( isset($_POST['updateMenuItems'])) {
	if( !update_menu_item_list($dbObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

/* add and delete menu groups records */
if( isset($_POST['updateMenuGroups'])) {
	if( !update_menu_groups($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}		

if( isset($_POST['updateGalleryItems'])) {
	if(!update_gallery_items($dbObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['updateHeaders'])) {
	if(!update_headers($dbObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['savePost'])) {
	if(!save_post($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['addUser'])) {
	if(!add_user($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['updateUser'])) {
	if(!update_user($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['updateTabs'])) {
	if(!update_tabs($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['updateRightbar'])) {
	if(!update_rightbar($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['updateArticles'])) {
	if(!update_articles($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

if( isset($_POST['changeTheme'])) {
	if(!change_theme($dbObj, $userObj)) {
		mysqli_rollback($dbObj->dbh);
		return false;
	}
}

mysqli_commit($dbObj->dbh);
return true;

}

/*------------------------------------------------------------------------------------------
 * changeTheme
 * 
 --------------------------------------------------------------------------------------------*/
function change_theme($dbObj,$userObj)
{
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " change_theme was called.<br>";
	
	if(isset($_POST['theme'])) {
		
		$sqlQuery = "update wb_user set theme = '" . $_POST['theme'] . "' where ID = '" . $userObj->ID . "'"; 
		
		if( ! $dbObj->query($sqlQuery)) {
			$dbObj->error =  "change_theme: an error occurred during mysqli_query<br><br>" . $dbObj->error .
			"<br><br>" . $sqlQuery;
			return false;
		}
		
		$userObj->theme = $_POST['theme'];
	}
	
	return true;
}

/*------------------------------------------------------------------------------------------
 * add_usergroup
 *
 -------------------------------------------------------------------------------------------*/
function add_usergroup($dbObj, $userObj) {
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " add_usergroup was called.<br>";
	
	$userId = $userObj->ID;
	$name = isset($_POST['name']) ? $_POST['name'] : NULL; 
	$descrip = isset($_POST['shortDescription']) ? $_POST['shortDescription'] : NULL;
	$lang = isset($_POST['lang']) ? $_POST['lang'] : NULL;
	
	if($name == NULL || $descrip == NULL || $lang == NULL) {
		$dbObj->error = "add_usergroup: record not saved: the name, description, or language field is not present.";
		return false;		
	}
	
	$sqlQuery = "insert into wb_usergroups ( name, shortDescription, lang ) ".
			'values("' . $name . '","' . $descrip . '","' . $lang . '")';
	
	if( ! $dbObj->query($sqlQuery)){
		$dbObj->error =  "add_group: an error occurred during mysqli_query<br><br>" . $dbObj->error .
		"<br><br>" . $sqlQuery;
		return false;
	}
	
	$successMessage = "User group '" . $name . "' has been successfully added to the database.";
	return true;
	
	
	
}

/*------------------------------------------------------------------------------------------
 * add_to_group
 *
 -------------------------------------------------------------------------------------------*/
function add_to_group($dbObj, $userObj) {
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " add_to_group was called.<br>" . 
		serialize($_POST) . "<br/>" .
		$contentFieldNames['user-group'] . "<br/>" .
		$contentFieldNames['owner'];
	
	$groupId = isset($_POST[$contentFieldNames['user-group']]) ? $_POST[$contentFieldNames['user-group']] : -1;
	$userId = isset($_POST[$contentFieldNames['owner']]) ? $_POST[$contentFieldNames['owner']] : -1;

	if($groupId == -1 or $userId == -1) {
		$dbObj->error = "add_to_group: record not saved: no groupId or no userId.";
		return false;
	}

	$sqlQuery = "insert into wb_grouplists ( groupId, userId ) ".
			"values('" . $groupId .
			"','" . $userId . "')";
	
	if( ! $dbObj->query($sqlQuery)){
		$dbObj->error =  "add_to_group: an error occurred during mysqli_query<br><br>" . $dbObj->error .
		"<br><br>" . $sqlQuery;
		return false;
	}
	
	$successMessage = "User has been successfully added to the user group.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * delete_from_grouplist
 *
 -------------------------------------------------------------------------------------------*/
function delete_from_grouplist($dbObj, $userObj) {

	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " delete_from_grouplist was called.<br>";
	
	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$indexName = "checkbox_" . $i;
		if( isset($_POST[$indexName])) {
			$sqlQuery = "delete from wb_grouplists where ID = '" . $_POST[$indexName] . "'";
			if( ! $dbObj->query($sqlQuery)){
				$dbObj->error = "function delete_from_grouplist: an error occurred during mysqli_query<br><br>" .
						$dbObj->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}
	}
	
	$successMessage = "Item(s) have been successfully deleted from the group list.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * delete_from_usergroup
 *
 -------------------------------------------------------------------------------------------*/
function delete_from_usergroup($dbObj, $userObj) {
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " delete_from_grouplist was called.<br>";

	$groupId = isset($_POST[$contentFieldNames['user-group']]) ? $_POST[$contentFieldNames['user-group']] : -1; 

	if($groupId == -1) {
		$dbObj->error = "delete_from_usergroup: the group id was not set.";
		return false;
	}

	/* delete from wb_usergroups */
	$sqlQuery = "delete from wb_usergroups where ID = '" . $groupId . "'";
	
	if(! $dbObj->query($sqlQuery)) {
		$dbObject->error = "delete_from_usergroup: an error occured during mysqli_query<br><br>" . 
		$dbObject->error .
		"<br><br>" . $sqlQuery;
		return false;
	}

	$sqlQuery = "delete from wb_grouplists where groupId = '" . $groupId . "'";
	
	if(! $dbObj->query($sqlQuery)) {
		$dbObject->error = "delete_from_usergroup: an error occured during mysqli_query<br><br>" .
				$dbObject->error .
				"<br><br>" . $sqlQuery;
		return false;
	}
	
	
	$successMessage = "The item was successfully delete from user groups";
	return true;
}

/*------------------------------------------------------------------------------------------
 * add_content
 *
 -------------------------------------------------------------------------------------------*/
function add_content($dbObject, $userObj, $addMenuGroup = true ) {

	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	global $langues;
	
	$debugMessage = $debugMessage . " add_content was called.<br>";

	/* Check for user priveledges */
	if( ! isset($_POST['ownerId'])){
		$dbObject->error = "add_content: Content owner not set in update request.";
		return false;
	}
	
	if( ($userObj->type < 2) and $userObj->ID <> $_POST['ownerId'] ) {
		$dbObject->error = "update_page: You can not update a page you don't own.";
		$debugMessage = $debugMessage . "userObj->type: " . $userObj->type . "<br>" .
				"userObj->ID: " . $userObj->ID . "<br>" . "ownerId: " . $_POST['ownerId'] . "<br>";
	
		return false;
	}
	
	
	$contentObj = new dbContent();

	$contentObj->build_update_object($contentObj, $userObj);
	//echo $contentFieldNames['permalink'] . "<br/>";
	//echo $_POST['permalink'] . "<br/>";
	//die($contentObj->dump());
	
	if( ! $contentObj->add_content($dbObject)) {
		$dbObject->error =  "add_content: " . $dbObject->error;
		return false;
	}

	if(isset($_POST['isItem'])) {
		if( !add_menu_item($dbObject,$contentObj->ID)) {
			return false;
		}
	}
	
	if( $addMenuGroup and isset($_POST['menuId']) and $_POST['menuId'] != 0) {
		$sqlQuery = "insert into wb_menugroups (parentId, contentId, mgseq, menuId ) " .
				"values(" . $_POST[$contentFieldNames['parent']] .
				"," . $contentObj->ID .
				"," . "1" .
				"," . $_POST['menuId'] . ")";

		if( ! $dbObject->query($sqlQuery)) {
			mysqli_rollback($dbObject->dbh);
			$dbObject->error = "add_content: an error occured during mysqli_query<br><br>" . $dbObject->error .
			"<br><br>" . $sqlQuery;
			return false;
		}

	}

	$successMessage = $langues['content-item-added'];
	return true;
}

/*------------------------------------------------------------------------------------------
 * update_content
 *
 -------------------------------------------------------------------------------------------*/
function update_content($dbObject, $userObj )
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " update_content was called.<br>";
	
	/* for any update of a content record, the currently existing defaultParentId must be set in the POST data. */
	if( ! isset($_POST['oldParentId'])) {
		$dbObject->error = "update_content: oldParentId not set in update request.";
		return false;
	}

	/* Check for user priveledges */
	if( !isset($_POST['ownerId']) or !isset($_POST['ownerType'] )){
		$dbObject->error = "update_content: Content ownerId or ownerType not set in update request.";
		return false;
	}

	/*You can update a page if your user priveledge is greater than the content owner's */
	if( $userObj->type < $_POST['ownerType'] and $userObj->ID <> $_POST['ownerId'] ) {
		$dbObject->error = "update_content: You can't update a page you don't own.<br/>";
		return false;
	}
	
	$contentObj = new dbContent();
	
	$contentObj->build_update_object($contentObj, $userObj);
	
	//die($contentObj->dump());
	
	/* build the update statement */
	$sqlQuery = "update wb_content set ";

	    if(isset($contentObj->defaultParentId))
			$sqlQuery = $sqlQuery . "defaultParentId = '" . $contentObj->defaultParentId . "', ";
		if(isset($contentObj->permalink))
			$sqlQuery = $sqlQuery . "permalink = '" . mysqli_real_escape_string( $dbObject->dbh, $contentObj->permalink ) . "', ";
		if(isset($contentObj->title))
			$sqlQuery = $sqlQuery . "title = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->title) . "', ";
		if(isset($contentObj->status))
			$sqlQuery = $sqlQuery . "status = '" . mysqli_real_escape_string($dbObject->dbh, $contentObj->status) . "', ";
		if(isset($contentObj->target))
			$sqlQuery = $sqlQuery . "target = '" . $contentObj->target . "', ";
		if(isset($contentObj->shortDescription))
			$sqlQuery = $sqlQuery . "shortDescription = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->shortDescription) . "', ";
		if(isset($contentObj->ownerId))
			$sqlQuery = $sqlQuery . "ownerId = '" . $contentObj->ownerId . "', ";
		if(isset($contentObj->pageType))
			$sqlQuery = $sqlQuery . "pageType = '" . $contentObj->pageType . "', ";
		if(isset($contentObj->pageArgument))
			$sqlQuery = $sqlQuery . "pageArgument = '" . $contentObj->pageArgument . "', ";
		if(isset($contentObj->galleryImage))
			$sqlQuery = $sqlQuery . "galleryImage = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->galleryImage) . "', ";
		if(isset($contentObj->articleFile))
			$sqlQuery = $sqlQuery . "articleFile = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->articleFile) . "', ";
		/*
		if(isset($contentObj->articleURL))
			$sqlQuery = $sqlQuery . "articleURL = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->articleURL) . "', ";
		*/	
		if(isset($contentObj->articleImage))
			$sqlQuery = $sqlQuery . "articleImage = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->articleImage) . "', ";
		if(isset($contentObj->articleDescription))
			$sqlQuery = $sqlQuery . "articleDescription = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->articleDescription) . "', ";
		if(isset($contentObj->ogType))
			$sqlQuery = $sqlQuery . "ogType = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->ogType) . "', ";
		if(isset($contentObj->authorFullName))
			$sqlQuery = $sqlQuery . "authorFullName = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->authorFullName) . "', ";
		if(isset($contentObj->authorLink))
			$sqlQuery = $sqlQuery . "authorLink = '" . mysqli_real_escape_string($dbObject->dbh,$contentObj->authorLink) . "', ";
				
		$sqlQuery = $sqlQuery . "dateModified = '" . $contentObj->dateModified . "' ";	
			
			
	$sqlQuery = $sqlQuery . "where ID = '" . $_POST['ID'] . "'";
	
	//die($sqlQuery);
	
	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "update_content: an error occurred during mysqli_query<br><br>" . $dbObject->error .
		"<br><br>" . $sqlQuery;
		return false;
	}

	/* if parent was modified, update the menu group records */
	if($_POST[$contentFieldNames['parent']] != $_POST['oldParentId']) {
		$sqlQuery = "update wb_menugroups set parentId = '" . $_POST[$contentFieldNames['parent']] . "' " .
				"where parentId = '" . $_POST['oldParentId'] . "' and contentId = '" . $_POST['ID'] . "'";
		if( ! $dbObject->query($sqlQuery)) {
			$dbObject->error = "update_content: an error occurred during mysqli_query<br><br>" . $dbObject->error .
			"<br><br>" . $sqlQuery;
			return false;
		}
	}
	
	$successMessage = "The content item has been successfully updated.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * add_menu
 -------------------------------------------------------------------------------------------*/
function add_menu( $dbObject,$userObj )
{
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " add_menu was called.<br>";

	$contentObj = new dbContent();
	$contentObj->build_update_object($contentObj, $userObj);
	
	if( ! $contentObj->add_content($dbObject)) {
		$dbObject->error =  "add_menu: " . $dbObject->error;
		return false;
	}

	$successMessage = "The menu has been successfully added to the database.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * delete_menu
 *
 -------------------------------------------------------------------------------------------*/
function delete_menu($dbObject, $userObj )
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " delete_menu was called.<br>";
	
	$menuId = isset($_POST[$contentFieldNames['menu-id']]) ? $_POST[$contentFieldNames['menu-id']] : 0; 
	if( $menuId == 0 ){
		$dbObject->error = "delete_menu: No menu ID. Nothing deleted.";
		return false;
	}
	
	$sqlQuery = "delete from wb_content where ID = '" . $menuId . "' and defaultParentId = '-1'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_menu: an error occurred during mysqli_query<br><br>" . 
			$dbObject->error . "<br><br>" . $sqlQuery;
		return false;
	}
	
	if(mysqli_affected_rows($dbObject->dbh) == 0) {
		$dbObject->error = "delete_menu: did not delete menu probably because it was a gallery page. Use delete page.";
		return false;
	}

	$sqlQuery = "delete from wb_menugroups where menuId = '" . $menuId . "'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}

	$sqlQuery = "delete from wb_menuitems where menuId = '" . $menuId . "'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}

	$successMessage = "The menu has been successfully deleted from the database.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * add_menu_item
 -------------------------------------------------------------------------------------------*/
function add_menu_item( wbDatabase $dbObject, $contentId = NULL )
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " add_menu_item was called.<br>";

	$menuItemObj = new wbMenuItem();

	$menuItemObj->menuId = isset($_POST[$contentFieldNames['menu-id']]) ? $_POST[$contentFieldNames['menu-id']] : 0; 
	$menuItemObj->menuType = isset($_POST[$contentFieldNames['menuType']]) ? $_POST[$contentFieldNames['menuType']] : 1;
	$menuItemObj->sequence = isset($_POST[$contentFieldNames['sequence']]) ? $_POST[$contentFieldNames['sequence']] : 1;
	if($contentId == NULL) {
		$menuItemObj->contentId = isset($_POST[$contentFieldNames['content-id']]) ? $_POST[$contentFieldNames['content-id']] : 0;
	} else {
		$menuItemObj->contentId = $contentId;
	}
	
	if($menuItemObj->menuId == 0 or $menuItemObj->contentId == 0) {
		$dbObject->error = "add_menu_item: some neccessary fields were not set in the post";
		return false;
	}
	
	if(!$menuItemObj->add($dbObject))
		return false;

	$successMessage = "The menu item has been successfully added to the database.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * Update_menu_item_list
 -------------------------------------------------------------------------------------------*/
function update_menu_item_list( $dbObject )
{
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " update_menu_item_list was called.<br/>";
	
	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$sequence = "sequence_" . $i;
		$indexName = "checkbox_" . $i;
		$itemId = "itemId_" . $i;
		
		/*
		$value = isset($_POST[$itemId]) ? $_POST[$itemId] : "";
		$seq = isset($_POST[$sequence]) ? $_POST[$sequence] : "";
		$debugMessage = $debugMessage . 
			$itemId . "=" . $value . " " . $sequence . "=" . $seq . "<br/>";
		
		continue;
		*/
		
		if(isset($_POST[$sequence])) {
			
			$value = isset($_POST[$itemId]) ? $_POST[$itemId] : "";
			$debugMessage = $debugMessage .
			$itemId . "=" . $value . " " . $sequence . "=" . $_POST[$sequence] . "<br/>";
				
			$sqlQuery = "update wb_menuitems set sequence='" . $_POST[$sequence] . "' where ID ='" . $_POST[$itemId] . "'";
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "function delete_menu_item: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}
		
		if( isset($_POST[$indexName])) {
			$sqlQuery = "delete from wb_menuitems where ID = '" . $_POST[$indexName] . "'";
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "function delete_menu_item: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
				return false;
			}
		}
	}

	$successMessage = "The items have been updated/removed from thier menus.";
	return true;
}

/*--------------------------------------------------------------------------------------
 * delete_from_menu_groups
 */
function delete_from_menu_groups($dbObj) {
	global $menuGroupFieldNames;
	global $debugMessage;
	
	$debugMessage = $debugMessage . " delete_from_menu_groups was called.<br>";
	
	if(!isset($_POST[$menuGroupFieldNames[0][1]]) or !isset($_POST[$menuGroupFieldNames[1][1]])) {
		$dbObj->error = "delete_from_menu_groups: some neccessary fields where not set in post.";		
		return false;
	}
	
	$sqlQuery = "delete from wb_menugroups where " .
			"parentId = '" . $_POST[$menuGroupFieldNames[0][1]] . "' and " .
			"contentId = '" . $_POST[$menuGroupFieldNames[1][1]] . "'";

	if( ! $dbObject->query($sqlQuery)){
		$dbObject->error = "function delete_from_menu_groups: an error occurred during mysqli_query<br><br>" .
				$dbObject->error . "<br><br>" . $sqlQuery;
		return false;
	}
	
	return true;
}

/*-------------------------------------------------------------------------------------------
 * add_menu_group_item
 ---------------------------------------------------------------------------------------------*/
function add_menu_group_item($dbObject)
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " add_menu_group_item was called.<br>";

	if(!isset($_POST[ $contentFieldNames['parent']]) or !isset($_POST[ $contentFieldNames['content-id']]) or
		!isset($_POST[ $contentFieldNames['sequence']]) or !isset($_POST[ $contentFieldNames['menu-id']])) {
			$dbObject->error = "add_menu_group: one or more neccessary fields were not set in the post data.";
			return false;
		}

	$sqlQuery = "insert into wb_menugroups( parentId, contentId, trackParent, mgseq, menuId ) ".
			"values('" . $_POST[ $contentFieldNames['parent']] .
			"','" . $_POST[ $contentFieldNames['content-id']] .
			"','1'" . 
			",'" . $_POST[ $contentFieldNames['sequence']] .
			"','" . $_POST[ $contentFieldNames['menu-id']] . "')";

	if( ! $dbObject->query($sqlQuery)){
		$dbObject->error = "an error occured during mysqli_query<br><br>" . $dbObject->error .
		"<br><br>" . $sqlQuery;
		return false;
	}

	$successMessage = "The menu groups update was successful.";
	return true;
}

/*------------------------------------------------------------------------------------------
 * delete_menu_group_item
 -------------------------------------------------------------------------------------------*/
function delete_menu_group_item( $dbObject, $parentId, $contentId )
{
	global $debugMessage;

	$debugMessage = $debugMessage . " delete_menu_item was called.<br>";

	$sqlQuery = "delete from wb_menugroups where parentId = " . $parentId . " and contentId = " . $contentId;
	if( ! $dbObject->query($sqlQuery)){
		$dbObject->error = "function delete_menu_group_item: an error occurred during mysqli_query<br><br>" .
				$dbObject->error . "<br><br>" . $sqlQuery;
		return false;
	}

	return true;
}

/*-----------------------------------------------------------------------------------------
 * add_headerRecord
 */
function add_headerRecord($dbObj) {
	
	global $debugMessage;

	$debugMessage = $debugMessage . " add_headerRecord was called.<br>";

	if( !isset($_POST['contentId']) or !isset($_POST['sequence']) or !isset($_POST['headerRecord'])) {
		$dbObject->error = "add_headerRecord: one or more neccessary fields were not set in the post data.";
		return false;
	}
	
	$debugMessage = $debugMessage . " contentId = " . $_POST['contentId'] . "<br>";
	$debugMessage = $debugMessage . " sequence = " . $_POST['sequence'] . "<br>";
	$debugMessage = $debugMessage . " headerRecord = " . htmlspecialchars($_POST['headerRecord']) . "<br>";
	
	
	$sqlQuery = "insert into wb_headers (contentId, seq, headerRecord) " .
			"values ('" . $_POST['contentId'] . "', '" . $_POST['sequence'] . "', '" .
			mysqli_real_escape_string($dbObj->dbh, $_POST['headerRecord']) . "')";
	if(!$dbObj->query($sqlQuery)) {
		$dbObj->error = "add_headerRecord: an error occurred during mysqli_query<br><br>" .
				$dbObj->error . "<br><br>" . $sqlQuery;
		return false;
	}
	
	return true;
}


/*-----------------------------------------------------------------------------------------
 * update_gallery_items
 */
function update_gallery_items($dbObject)
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	
	$debugMessage = $debugMessage . " update_gallery_items was called.<br>";

	if( ! isset($_POST['recordCount']) or ! isset($_POST['parentId'])) {
		$dbObject->error = "update_gallery_items: one or more neccessary fields were not set in post data.<br>";
		return false;
	}
	
	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$sequence = isset($_POST['sequence_' . $i]) ? $_POST['sequence_' . $i ] : NULL;
		$itemId = isset($_POST['itemId_' . $i]) ?  $_POST['itemId_' . $i] : NULL;
		$contentId = isset($_POST['contentId_' . $i]) ? $_POST['contentId_' . $i] : NULL;
		$deleteId = isset($_POST['checkbox_' . $i]) ?  $_POST['checkbox_' . $i] : NULL;
		$image = isset($_POST['image_' . $i]) ? $_POST['image_' . $i] : NULL;
		
		$debugMessage = $debugMessage . " sequence = " . $sequence . "<br>";
		$debugMessage = $debugMessage . " itemId = " . $itemId . "<br>";
		
		/* update sequence */
		if(isset($sequence) and isset($itemId)) {
			$sqlQuery = "update wb_menuitems set sequence = '" . $sequence . "' where ID = '" . $itemId . "'";
			if(!$dbObject->query($sqlQuery)) {
				$dbObject->error = "update_gallery_items: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
				return false;
			}
		}

		/* update gallery image */
		if( isset($contentId) and isset($image)) {
			$sqlQuery = "update wb_content set galleryImage = '" . $image . "' where ID = '" . $contentId . "'";
			if(!$dbObject->query($sqlQuery)) {
				$dbObject->error = "update_gallery_items: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
				return false;
			}
				
		}
		
		/* delete items */
		if( isset($deleteId)) {
			$sqlQuery = "delete from wb_menuitems where ID = " . $deleteId;
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "update_gallery_items: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
				return false;
			} else {
				$parentId = $_POST['parentId'];
				$contentId= $_POST['contentId_' . $i];
				if( ! delete_menu_group_item( $dbObject, $parentId, $contentId )) {
					return false;
				}
				
			}
		}
	}
	
	if( isset($_POST['checkbox_add']))
	{
		if(!add_menu_item($dbObject, $_POST[$contentFieldNames['content-id']]))
			return false;

		if(!add_menu_group_item($dbObject))
			return false;
	}
	
	$successMessage = "Gallery items were successfully updated";
	return true;

}

/*------------------------------------------------------------------------------------
 * update_headers($dbObj)
 */
function update_headers($dbObj) {

	global $headersFieldNames;
	global $debugMessage;

	$debugMessage = $debugMessage . " update_headers was called.<br>";

	if( ! isset($_POST['recordCount']) or ! isset($_POST['contentId'])) {
		$dbObj->error = "update_headers: one or more neccessary fields were not set in post data.<br>";
		return false;
	}
	
	for( $i = 1; $i <= $_POST['recordCount']; $i++) {

		$itemId = isset($_POST['itemId_' . $i]) ? $_POST['itemId_' . $i ] : NULL;
		$sequence = isset($_POST['sequence_' . $i]) ? $_POST['sequence_' . $i ] : NULL;
		$deleteId = isset($_POST['checkbox_' . $i]) ?  $_POST['checkbox_' . $i] : NULL;
		
		$debugMessage = $debugMessage . " sequence = " . $sequence . "<br>";
		$debugMessage = $debugMessage . " deleteId = " . $deleteId . "<br>";
		
		/* update sequence */
		if(isset($sequence) and isset($itemId)) {
			$sqlQuery = "update wb_headers set seq = '" . $sequence . "' where ID = '" . $itemId . "'";
			if(!$dbObj->query($sqlQuery)) {
				$dbObj->error = "update_headers: an error occurred during mysqli_query<br><br>" .
						$dbObj->error . "<br><br>" . $sqlQuery;
				return false;
			}
		}
		
		if( isset($deleteId)) {
			$sqlQuery = "delete from wb_headers where ID = " . $deleteId;
			if( ! $dbObj->query($sqlQuery)){
				$dbObj->error = "update_hheaders: an error occurred during mysqli_query<br><br>" .
						$dbObj->error . "<br><br>" . $sqlQuery;
				return false;
			}
		} 
	}/* close for loop*/
	
	if( isset($_POST['checkbox_add']))
	{
		if(!add_headerRecord($dbObj))
			return false;
	}
	
	return true;
}

/*------------------------------------------------------------------------------------------
 * update_menu_groups
 *
 *
 -------------------------------------------------------------------------------------------*/
function update_menu_groups($dbObject, $userObj )
{
	global $debugMessage;

	$debugMessage = $debugMessage . " update_menu_groups was called.<br>";

	/* Check for user priveledges */
	if( ! isset($_POST['ownerId'])){
		$dbObject->error = "update_menu_groups: Content owner not set in update request.";
		return false;
	}

	if( ($userObj->type < 4) and $userObj->ID <> $_POST['ownerId']) {
		$dbObject->error = "update_menu_groups: You can not update a page you don't own.";
		$debugMessage = $debugMessage . "userObjType = " . $userObj->type . "<br>";
		$debugMessage = $debugMessage . "userObjID = " . $userObj->ID . "<br>";
		$debugMessage = $debugMessage . "OwnerId = " . $_POST['ownerId'] . "<br>";
		return false;
	}

	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$indexName = "checkbox_" . $i;
		if( isset($_POST[$indexName])) {
			$sqlQuery = "delete from wb_menugroups where ID = " . $_POST[$indexName];
			$dbObject->query($sqlQuery);
		}
	}

	if( isset($_POST['addMenuGroup'])) {
		if( ! add_menu_group_item($dbObject)) {
			return false;
		}
	}

	return true;
}

/*------------------------------------------------------------------------------------------
 * add_tab_item
 *
 *
 -------------------------------------------------------------------------------------------*/
function add_tab_item($dbObject, $userObj )
{
	global $debugMessage;
	
	$debugMessage = $debugMessage . " add_tab_item was called.<br>";
	
	$contentId = isset($_POST['contentId']) ?
		$_POST['contentId'] : NULL;
	
	$tabTitle = isset($_POST['tabTitle']) ?
		$_POST['tabTitle'] : NULL;
	
	$sequence = isset($_POST['sequence']) ?
		$_POST['sequence'] : NULL;
	
	$sqlQuery = "insert into wb_articles ( contentId, tabTitle, sequence, byteCount ) " .
		'values("' . 
		$contentId . '", "' . 
		$tabTitle . '", "' .
		$sequence . '", "0")';
	
	if( ! $dbObject->query($sqlQuery)) {	
		$dbObject->error = "add_tab_item: an error occurred during mysqli_query<br><br>" .
				$dbObject->error . "<br><br>" . $sqlQuery;
		return false;
	}
	
	return true;
}

/*------------------------------------------------------------------------------------------
 * update_tabs
 *
 *
 -------------------------------------------------------------------------------------------*/
function update_tabs(wbDatabase $dbObject, dbUser $userObj )
{
	global $debugMessage;
	
	$debugMessage = $debugMessage . " update_tabs was called.<br>";

	$contentId = isset($_POST['contentId']) ?  $_POST['contentId'] : NULL;
	$debugMessage = $debugMessage . " contentId = " . $contentId . "<br>";
	
	
	if( ! isset($_POST['recordCount'])) {
		$dbObject->error = "update_tabs: one or more neccessary fields were not set in post data.<br>";
		return false;
	}
	
	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$sequence = isset($_POST['seq_' . $i]) ? $_POST['seq_' . $i ] : NULL;
		$articleId = isset($_POST['articleId_' . $i]) ?  $_POST['articleId_' . $i] : NULL;
		$tabTitle = isset($_POST['tabTitle_' . $i]) ? $_POST['tabTitle_' . $i] : NULL;
		$checkboxId = isset($_POST['checkbox_' . $i]) ? $_POST['checkbox_' . $i] : NULL;
		
		$debugMessage = $debugMessage . " &nbsp;articleId = " . $articleId . "<br>";
		$debugMessage = $debugMessage . " &nbsp;sequence = " . $sequence . "<br>";
		$debugMessage = $debugMessage . " &nbsp;tabTitle = " . $tabTitle . "<br>";
		$debugMessage = $debugMessage . " &nbsp;checkboxId = " . $checkboxId . "<br>";
		
		/* update wb_articles */
		$sqlQuery = "update wb_articles set sequence = '" . $sequence . "', " .
		'tabTitle = "' . $tabTitle . '" ' .
		"where ID = '" . $articleId . "'";
		
		if(!$dbObject->query($sqlQuery)) {
			$dbObject->error = "update_tabs: an error occurred during mysqli_query<br><br>" .
					$dbObject->error . "<br><br>" . $sqlQuery;
					return false;
		}
		
		if( isset($checkboxId) ) {
			$sqlQuery = "delete from wb_articles where ID = '" . $checkboxId . "'";
				
			if(!$dbObject->query($sqlQuery)) {
				$dbObject->error = "update_tabs: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}
		
	}
	
	if( isset($_POST['addTab'])) {
		if( ! add_tab_item($dbObject,$userObj)) {
			return false;
		}
	}

	return true;
}

/*------------------------------------------------------------------------------------------
 * update_rightbar
 *
 *
 -------------------------------------------------------------------------------------------*/
function update_rightbar(wbDatabase $dbObject, dbUser $userObj )
{
	global $debugMessage;

	$debugMessage = $debugMessage . " update_rightbar was called.<br>";

	$contentId = isset($_POST['contentId']) ?  $_POST['contentId'] : 0;
	$debugMessage = $debugMessage . " contentId = " . $contentId . "<br>";


	if( ! isset($_POST['recordCount'])) {
		$dbObject->error = "update_rightbar: one or more neccessary fields were not set in post data.<br>";
		return false;
	}

	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		
		$checkboxId = isset($_POST['checkbox_' . $i]) ? $_POST['checkbox_' . $i] : NULL;
		$menuID = isset($_POST['menuID_' . $i]) ? $_POST['menuID_' . $i] : NULL;
		$sequence = isset($_POST['seq_' . $i]) ? $_POST['seq_' . $i ] : NULL;

		$debugMessage = $debugMessage . " &nbsp;checkboxId = " . $checkboxId . "<br>";
		$debugMessage = $debugMessage . " &nbsp;menuID = " . $menuID . "<br>";
		$debugMessage = $debugMessage . " &nbsp;sequence = " . $sequence . "<br>";

		/* update wb_articles */
		$sqlQuery = "update wb_menuitems set sequence = '" . $sequence . "' " .
				"where ID = '" . $menuID . "'";

		if(!$dbObject->query($sqlQuery)) {
			$dbObject->error = "update_rightbar: an error occurred during mysqli_query<br><br>" .
					$dbObject->error . "<br><br>" . $sqlQuery;
					return false;
		}
		
		if( isset($checkboxId) ) {
			$sqlQuery = "delete from wb_menuitems where ID = '" . $checkboxId . "'";

			if(!$dbObject->query($sqlQuery)) {
				$dbObject->error = "update_rightbar: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}

	}

	if( isset($_POST['addRightbar'])) {
		if( ! add_menu_item($dbObject,$contentId)) {
			return false;
		}
	}

	return true;
}

/*------------------------------------------------------------------------------------------
 * update_articles
 *
 *
 -------------------------------------------------------------------------------------------*/
function update_articles(wbDatabase $dbObject, dbUser $userObj )
{
	global $debugMessage;

	$debugMessage = $debugMessage . " update_articles was called.<br>";

	$contentId = isset($_POST['contentId']) ?  $_POST['contentId'] : 0;
	$debugMessage = $debugMessage . " contentId = " . $contentId . "<br>";


	if( ! isset($_POST['recordCount'])) {
		$dbObject->error = "update_articles: one or more neccessary fields were not set in post data.<br>";
		return false;
	}

	for( $i = 1; $i <= $_POST['recordCount']; $i++) {

		$checkboxId = isset($_POST['checkbox_' . $i]) ? $_POST['checkbox_' . $i] : NULL;
		$menuID = isset($_POST['menuID_' . $i]) ? $_POST['menuID_' . $i] : NULL;
		$sequence = isset($_POST['seq_' . $i]) ? $_POST['seq_' . $i ] : NULL;

		$debugMessage = $debugMessage . " &nbsp;checkboxId = " . $checkboxId . "<br>";
		$debugMessage = $debugMessage . " &nbsp;menuID = " . $menuID . "<br>";
		$debugMessage = $debugMessage . " &nbsp;sequence = " . $sequence . "<br>";

		/* update wb_articles */
		$sqlQuery = "update wb_menuitems set sequence = '" . $sequence . "' " .
				"where ID = '" . $menuID . "'";

		if(!$dbObject->query($sqlQuery)) {
			$dbObject->error = "update_articles: an error occurred during mysqli_query<br><br>" .
					$dbObject->error . "<br><br>" . $sqlQuery;
					return false;
		}

		if( isset($checkboxId) ) {
			$sqlQuery = "delete from wb_menuitems where ID = '" . $checkboxId . "'";
			
			$debugMessage = $debugMessage . " &nbsp;deleting</br>";
			$debugMessage = $debugMessage . " &nbsp;checkboxId = " . $checkboxId . "<br>";
			$debugMessage = $debugMessage . " &nbsp;menuID = " . $menuID . "<br>";
			$debugMessage = $debugMessage . " &nbsp;sequence = " . $sequence . "<br>";
				
			if(!$dbObject->query($sqlQuery)) {
				$dbObject->error = "update_articles: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}

	}

	if( isset($_POST['addArticle'])) {
		if( ! add_menu_item($dbObject,$contentId)) {
			return false;
		}
	}

	return true;
}

/*------------------------------------------------------------------------------------------
 * delete_page
 *
 -------------------------------------------------------------------------------------------*/
function delete_content($dbObject, $userObj )
{
	global $contentFieldNames;
	global $debugMessage;
	global $successMessage;
	global $langues;
	
	$debugMessage = $debugMessage . " delete_content was called.<br>";
	
	/* Check for user priveledges */
	if( ! isset($_POST['ownerId'])){
		$dbObject->error = "delete_page: Content owner not set in delete request.";
		return false;
	}
	
	if( $userObj->ID <> $_POST['ownerId'] ) {
		$dbObject->error = "delete_page: You can not delete a page you don't own.";
		return false;
	}
	
	$sqlQuery = "delete from wb_content where ID = '" . $_POST['ID'] . "'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}

	$sqlQuery = "delete from wb_menugroups where contentId = '" . $_POST['ID'] . "'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}

	$sqlQuery = "delete from wb_menuitems where contentId = '" . $_POST['ID'] . "'";

	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}

	$sqlQuery = "delete from wb_articles where contentId = '" . $_POST['ID'] . "'";
	
	if( ! $dbObject->query($sqlQuery)) {
		$dbObject->error = "delete_page: an error occurred during mysqli_query<br><br>" . $sqlQuery;
		return false;
	}
	
	$successMessage = 'content-item-deleted';
	return true;
}

/*--------------------------------------------------------------------------------------
 * save_post()
 *
 *
 --------------------------------------------------------------------------------------*/
function save_post($dbObject,$userObj)
{
	global $debugMessage;
	
	$debugMessage = $debugMessage . " save_post was called.<br>";
	
	/* Check for Id */
	$contentId = isset($_POST['Id']) ? $_POST['Id'] : NULL; 
	if( ! isset($contentId) or strlen($contentId) == 0 ) {
		$dbObject->error = "save_post: ContentId not set in update request.<br/>";
		return false;
	}
	
	/* Check for user priveledges */
	if( ! isset($_POST['ownerId']) or !isset($_POST['ownerType'])){
		$dbObject->error = "save_post: Content ownerId or ownerType not set in update request.<br/>";
		return false;
	}
	
	/*You can update a post if your user priveledge is greater than the content owner's 
	 * and your in the same group as the content's status */
	$contentStatus = isset($_POST['contentStatus']) ? $_POST['contentStatus'] : ""; 
	if( $userObj->type <= $_POST['ownerType'] and $userObj->ID <> $_POST['ownerId'] and 
			!in_array($contentStatus,$userObj->groupsArray) ) {
		$dbObject->error = "save_post: You can't update a page you don't own.<br/>";
		return false;
	}
	
	/* check for editor */
	if( ! isset($_POST['editor'])){
		$dbObject->error = "save_post: Article text not set in update request.<br/>";
		return false;
	}
	
	$tabTitle = isset($_GET['tab']) ? $_GET['tab'] : '';
	if(strlen($tabTitle) == 0) {
		$tabTitle = isset($_POST['tabTitle']) ? $_POST['tabTitle'] : '';
		if(strlen($tabTitle) == 0) {
			$dbObject->error = "save_post: tabTitle not set for database update.<br/>";
			return false;
		}
	}

	$debugMessage = $debugMessage . " contentId = " . $contentId . "<br>";
	
	//get rid of any java script;
	$saveString = mysqli_real_escape_string($dbObject->dbh,$_POST['editor']);
	$saveString = preg_replace('#(<script>).*?(</script>)#','\n', $saveString);
	//$saveString = $_POST['editor'];
	//die($saveString);
	/* build the insert on duplicate update statement */
	$sqlQuery = "insert into wb_articles (contentId, tabTitle, sequence, articleText, byteCount) " .
	'values("' . $_POST['Id'] . '", "' .
	$tabTitle . '", "1", "' .
	$saveString . '", "' . 
	strlen($saveString) . '") ' .
	"on duplicate key update articleText = '" . $saveString . "', " .
	" byteCount = '" . strlen($saveString) . "'";  
	
	if( ! $dbObject->query($sqlQuery)){

		$dbObject->error = "save_post: an error occured during mysqli_query<br><br>" . 
			$dbObject->error . "<br><br>" . $sqlQuery;

		$debugMessage = $debugMessage . "save_post() returned false<br>";

		return false;
	}
	
	$debugMessage = $debugMessage . "save_post() returned true<br>";
	return true;
}	

/*--------------------------------------------------------------------------------------
 * add_user()
 *
 *
 --------------------------------------------------------------------------------------*/
function add_user($dbObject,$userObj)
{
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " add_user was called.<br>";

	/* check for neccessary fields */
	if( ! isset($_POST['username'])){
		$dbObject->error = "add_user: username not set in update request.";
		return false;
	}

	if( ! isset($_POST['password'])){
		$dbObject->error = "add_user: password not set in update request.";
		return false;
	}
	
	if( ! isset($_POST['type'])){
		$dbObject->error = "add_user: 'type' not set in update request.";
		return false;
	}
	
	
	/* check user priviledges */
	if( $userObj->type < 4) {
		$dbObject->error = "add_user: You don't have permission to add users.";
		return false;
	}
	
	$newContent = new dbContent();
	$newContent->build_update_object($newContent,$userObj);
	$newContent->target = "_self";
	$newContent->status = "Public";
	$newContent->ownerId = $userObj->ID;
	
	$newContent->shortDescription = $newContent->title . " profile page";
	if(! $newContent->add_content($dbObject)){
		$dbObject->error = "add_user: an error occured:<br>" .
				serialize($_POST) . "<br/>" .
				$dbObject->error;
		return false;		
	}
	
	$newUser = new dbUser();
	$newUser->profileId = $newContent->ID;
	$newUser->username = $_POST['username'];
	$newUser->password = $_POST['password'];
	$newUser->type = $_POST['type'];
	$newUser->fullName = $newContent->title;
	$newUser->addedBy = $userObj->ID;
	
	if(! $newUser->add_user($dbObject)) {
		$dbObject->error = "add_user: an error occured:<br><br>" .
				$dbObject->error;
		return false;
	}
	
	$sqlQuery = "update wb_content set ownerId = '" . $newUser->ID . 
	"' where ID = '" . $newContent->ID . "'";
	
	if( ! $dbObject->query($sqlQuery)){
		$dbObject->error = "add_user: an error occured during mysqli_query<br><br>" .
				$dbObject->error . "<br><br>" . $sqlQuery;
				return false;
	}

	$successMessage = "The user was successfully added to the database.";
	return true;
}

/*--------------------------------------------------------------------------------------
 * update_user()
 *
 *
 --------------------------------------------------------------------------------------*/
function update_user($dbObject,$userObj)
{
	global $debugMessage;
	global $successMessage;

	$debugMessage = $debugMessage . " update_user was called.<br/>";

	for( $i = 1; $i <= $_POST['recordCount']; $i++) {
		$type = "type_" . $i;
		$indexName = "checkbox_" . $i;
		$userId = "userId_" . $i;
	
		$debugMessage = $debugMessage . $userId . "<br/>";
	
		if(isset($_POST[$userId])) {
			$debugMessage = $debugMessage . serialize($_POST) . "<br/>" .
				"userId = " . $_POST[$userId] . "<br/>" . 
				"type = " . $_POST[$type] . "<br/>";
			
			$sqlQuery = "update wb_user set type = '" . $_POST[$type] . "' where ID ='" . $_POST[$userId] . "'";
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "function update_user: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}
	
		if( isset($_POST[$indexName])) {
			if($_POST[$indexName] == $userObj->ID) {
				$dbObject->error = "delete of user aborted. You can't delete yourself";
				return;
			}
			$sqlQuery = "delete from wb_user where ID = '" . $_POST[$indexName] . "'";
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "update_user: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
			
			$sqlQuery = "update wb_content set ownerId = '" . $userObj->ID . "' " .
			"where ownerId = '" . $_POST[$indexName] . "'";
			if( ! $dbObject->query($sqlQuery)){
				$dbObject->error = "update_user: an error occurred during mysqli_query<br><br>" .
						$dbObject->error . "<br><br>" . $sqlQuery;
						return false;
			}
		}
	}
	
	$successMessage = "The user(s) where succesfully removed/updated.";
	return true;
}


?>