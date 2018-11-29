<?php

use workbench\utils\StringUtils;

/*--------------------------------------------------------------------------------------------
 * dbContent.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/class dbContent extends dbObject
{
	//econtent attributes
	public $ID = NULL;
	public $guid = NULL;
	public $lang = NULL;
	public $defaultParentId = NULL;
	public $permalink = NULL;
	public $title = NULL;
	public $status = NULL;
	public $target = NULL;
	public $shortDescription = NULL;
	public $creatorId = NULL;
	public $ownerId = NULL;
	public $ownerType = NULL;
	public $pageType = NULL;
	public $canEdit = NULL;
	public $hasRightbar = NULL;
	public $pageArgument = NULL;
	public $galleryImage = NULL;

	public $articleFile = NULL;
	public $articleImage = NULL;
	public $articleDescription = NULL;
	public $ogType = NULL;
	public $authorFullName = NULL;
	public $authorLink = NULL;
	public $dateCreated = NULL;
	public $dateModified = NULL;
	//parent query
	public $parentId = 0;
	public $grandParentId = 0;
	public $parentTitle = NULL;
	public $parentPermalink = NULL;
	public $parentPageType = NULL;
	//owner query
	public $ownerName = NULL;
	public $ownerImage = NULL;
	public $ownerBio = NULL;
	//directories
	public $sourceDir = NULL;
	public $themeDir = NULL;
	public $contentDir = NULL;
	//editor type
	public $editorType = 'Codemirror';

	/*----------------------------------------------------------------------------------------------
	 *
	 */
	public function selectHandler(wbDatabase $db) {
		
		global $errorMessage;
	
		if($db->getRowCount() == 0) {
			$this->db_error = "There is no" . " " . SITE_NAME . SUBSITE_NAME . $this->permalink . "<br><br>";
			$this->pageType = "error";
			return false;
		}
	
		foreach($db->result as $row) { //There will only be one row
	
			$this->ID = $row['ID'];
			$this->guid = $row['guid'];
			$this->lang = $row['lang'];
			$this->defaultParentId = $row['defaultParentId'];
			$this->permalink = htmlspecialchars($row['permalink']);
			$this->title = $row['title'];
			$this->status = $row['status'];
			$this->target = $row['target'];
			$this->shortDescription = $row['shortDescription'];
			$this->creatorId = $row['creatorId'];
			$this->ownerId = $row['ownerId'];
			$this->ownerType = $row['ownerType'];
			$this->pageArgument = $row['pageArgument'];
			$this->pageType = $row['pageType'];
			$this->canEdit = $row['canEdit'];
			$this->hasRightbar = $row['hasRightbar'];
			$this->galleryImage = $row['galleryImage'];
			$this->articleFile = $row['articleFile'];
			/*
			$this->articleURL = $row['articleURL'];
			*/
			$this->articleImage = $row['articleImage'];
			$this->articleDescription = $row['articleDescription'];
			$this->ogType = $row['ogType'];
			$this->authorFullName = $row['authorFullName'];
			$this->authorLink = $row['authorLink'];
			$this->dateCreated = $row['dateCreated'];
			$this->dateModified = $row['dateModified'];
			//parent query
			if(isset($row['parentId'])) {
				$this->parentId = $row['parentId'];
				$this->grandParentId = $row['grandParentId'];
				$this->parentPermalink = $row['parentPermalink'];
				$this->parentTitle = $row['parentTitle'];
				$this->parentPageType = $row['parentPageType'];
	
			}
			else {
				$this->parentId = $row['defaultParentId'];
			}
	
		}
	
		return true;
	
	}
	
	/*-------------------------------------------------------------------------------------------
	 * get_content
	 * 
	 *  Lookup by permalink
	 */
	public function get_content(wbDatabase $db, dbUser $userObj) {
	
		global $errorMessage;
		
		if( isset($_GET['p']) and $_GET['p'] != 0) {
			$this->sqlSelect = "select vw_content.ID,  vw_content.guid,  vw_content.lang, " .
			"vw_content.defaultParentId,  vw_content.permalink,  vw_content.title, " .
			"vw_content.status, vw_content.target,  vw_content.shortDescription, " .
			"vw_content.creatorId,  vw_content.ownerId, vw_content.ownerType, " .
			"vw_content.pageType,  vw_content.canEdit, vw_content.hasRightbar, " .
			"vw_content.pageArgument, " .
			"vw_content.galleryImage, vw_content.articleFile, " /*vw_content.articleURL, */ . 
			"vw_content.articleImage, vw_content.articleDescription, " .
			"vw_content.ogType, vw_content.authorFullName,  vw_content.authorLink, vw_content.dateCreated, vw_content.dateModified, " .
			"wb_content.ID parentId, wb_content.defaultParentId grandParentId, " .
			"wb_content.permalink parentPermalink, wb_content.title parentTitle, wb_content.pageType parentPageType " .
				
			"from vw_content left join wb_content on wb_content.ID = " . $_GET['p'] . " " .
			"where vw_content.permalink = '" . $this->permalink .
			"' and ( vw_content.ownerId = '" . $userObj->ID . "' or vw_content.status in (" . $userObj->groups() . ") or vw_content.status = 'Public' )";
			}
		else {
			$this->sqlSelect = "select vw_content.ID,  vw_content.guid,  vw_content.lang, " .
			"vw_content.defaultParentId,  vw_content.permalink,  vw_content.title, " .
			"vw_content.status, vw_content.target,  vw_content.shortDescription, " .
			"vw_content.creatorId,  vw_content.ownerId, vw_content.ownerType, " .
			"vw_content.pageType,  vw_content.canEdit, vw_content.hasRightbar, " .
			"vw_content.pageArgument, vw_content.galleryImage, " .
			"vw_content.articleFile, " /*vw_content.articleURL, */ . " vw_content.articleImage, vw_content.articleDescription, " .
			"vw_content.ogType, vw_content.authorFullName,  vw_content.authorLink, vw_content.dateCreated, vw_content.dateModified, " .
			"wb_content.ID parentId, wb_content.defaultParentId grandParentId, " .
			"wb_content.permalink parentPermalink, wb_content.title parentTitle, wb_content.pageType parentPageType " .
			
			"from vw_content left join wb_content on vw_content.defaultParentId = wb_content.ID " .
			"where vw_content.permalink = '" . $this->permalink .
			"' and ( vw_content.ownerId = '" . $userObj->ID . "' or vw_content.status in (" . $userObj->groups() . ") or vw_content.status = 'Public' )";
		}
	
		if( ! $db->query_all($this->sqlSelect)) {
			$this->db_error = $db->error . "<p/>";
			if(INCLUDE_SQL_IN_ERROR_MESSAGE) {
				$this->db_error = $this->db_error . $this->sqlSelect . "<p/>";
			}
			return false;
		}
		
		$ret = $this->selectHandler($db);

		if(isset($_GET['gp']) and $_GET['gp'] != 0) $this->grandParentId = $_GET['gp'];

		return $ret;
	}

	/*-------------------------------------------------------------------------------------------
	 * get_owner_info:
	 *
	 -----------------------------------------------------------------------------------------*/
	public function get_owner_info(wbDatabase $db) {
		
		$contentOwner = new dbUser();
		$contentOwner->ID = $this->ownerId;
		$contentOwner->get_user_bio($db, $this->lang);
		
		$this->ownerName = $contentOwner->fullName;
		$this->ownerImage = $contentOwner->profileImage;
		$this->ownerBio = $contentOwner->bio;
	
	}
	
	/*-------------------------------------------------------------------------------------------
	 * get_content_by_id:
	 * 
	 * Lookup by contentId
	 -----------------------------------------------------------------------------------------*/
	public function get_content_by_id(wbDatabase $db, dbUser $userObj) {
		global $debugMessage;
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_content_by_id() was called with contentId = " . $this->ID . "<br/>";

		$this->sqlSelect = "select vw_content.ID,  vw_content.guid,  vw_content.lang, " .
				"vw_content.defaultParentId,  vw_content.permalink,  vw_content.title, " .
				"vw_content.status, vw_content.target,  vw_content.shortDescription, " .
				"vw_content.creatorId,  vw_content.ownerId, vw_content.ownerType, " .
				"vw_content.pageType,  vw_content.canEdit, vw_content.hasRightbar, " .
				"vw_content.pageArgument, vw_content.galleryImage, " .
				"vw_content.articleFile, " /*vw_content.articleURL, */ . 
				"vw_content.articleImage, vw_content.articleDescription, " .
				"vw_content.ogType, vw_content.authorFullName,  vw_content.authorLink, vw_content.dateCreated, vw_content.dateModified, " .
				"wb_content.ID parentId, wb_content.defaultParentId grandParentId, " .
				"wb_content.permalink parentPermalink, wb_content.title parentTitle, wb_content.pageType parentPageType " .
					
				"from vw_content left join wb_content on vw_content.defaultParentId = wb_content.ID " .
				"where vw_content.ID = '" . $this->ID .
				"' and ( vw_content.ownerId = '" . $userObj->ID . "' or vw_content.status in (" . $userObj->groups() . ") or vw_content.status = 'Public' )";
		
		if( ! $db->query_all($this->sqlSelect)) {
			$this->db_error = $db->error . "<p/>";
			$this->db_error = $this->db_error . $this->sqlSelect . "<p/>";
			return false;
		}
		
		return $this->selectHandler($db);
		
	}
	
	/*--------------------------------------------------------------------------------------
	 * add_content
	 */
	function add_content(wbDatabase $db)
	{
	
		$sqlQuery = "insert into wb_content (guid, lang, defaultParentId, permalink, title, status, target, " .
				"shortDescription, ownerId, pageType, pageArgument, galleryImage, articleFile ) " .
				"values('" . $this->guid . "','" . $this->lang . "','" . $this->defaultParentId .
				"','" . $db->escapeString($this->permalink) .
				"','" . $db->escapeString($this->title) .
				"','" . $this->status .
				"','" . $this->target .
				"','" . $db->escapeString($this->shortDescription) .
				"','" . $this->ownerId .
				"','" . $this->pageType .
				"','" . $this->pageArgument .
				"','" . $db->escapeString($this->galleryImage) .
				"','" . $db->escapeString($this->articleFile) . "')";
	
		if( ! $db->query($sqlQuery)){
			$db->error = "add_content: an error occurred during mysqli_query<br><br>" . $db->error .
			"<br><br>" . $sqlQuery;
			return false;
	
		}
	
		$this->ID = $db->getInsertId();
	
		return true;
	}
	
	
	/*--------------------------------------------------------------------------------------------
	 * 
	 */
	public function set_permalink($permalink, $subsiteName){

		//strip off get map
		$this->permalink = StringUtils::str_concat_at($permalink, '?');
		
		//if there's a subsite name, strip it off
		$replace = "";
		$one = 1;
		$this->permalink =  StringUtils::str_replace_first($subsiteName,$replace,$this->permalink,$one);
		
		if($this->permalink == "/")
			$this->permalink = "/en";
		
		$this->lang = substr($this->permalink,1,2);	
		
	}

	/*---------------------------------------------------------------------------------------------
	 * 
	 */
	function set_directories(dbUser $userObj) {
	
		//override user setting
		if( isset($userObj->theme)) {
			$this->theme = $userObj->theme;
			$this->sourceDir = ABSDIR . "\\themes" . "\\" . $userObj->theme;
			$this->themeDir = SITE_NAME . SUBSITE_NAME . '/themes' . "/" . $userObj->theme;
		} else if( strlen(THEME) != 0 ){
			$this->theme = THEME;
			$this->sourceDir = ABSDIR . "\\themes" . "\\" . THEME;
			$this->themeDir = SITE_NAME . SUBSITE_NAME . '/themes' . "/" . THEME;
		}	else {
			$this->theme = "default";
			$this->sourceDir = ABSDIR . "\\themes" . "\\default";
			$this->themeDir = SUBSITE_NAME . '/themes' . "/default";
		}
	
		$this->contentDir = SITE_NAME . SUBSITE_NAME . '/wb-content/' . DATABASE;
	}
		
	/*--------------------------------------------------------------------------------------
	 * Build content update object
	 */
	function build_update_object(dbContent $contentObj, dbUser $userObj) {
	
		global $contentFieldNames;
	
		$contentObj->lang = isset($_POST['languageCode']) ?
		trim($_POST['languageCode'],'/') : 'en_US';
	
		$contentObj->guid = isset($_POST['guid']) ?
		$_POST['guid'] : strtolower(trim(com_create_guid(),'{}'));
	
		$contentObj->parentPermalink = isset($_POST['parentPermalink']) ?
		$_POST['parentPermalink'] : "/" . substr($this->lang,0,2);
	
		$contentObj->defaultParentId = isset($_POST[$contentFieldNames['parent']]) ?
		$_POST[$contentFieldNames['parent']] : NULL;
	
		$contentObj->permalink = isset($_POST[$contentFieldNames['permalink']]) ?
		$_POST[$contentFieldNames['permalink']] : NULL;
	
		$contentObj->title = isset($_POST[$contentFieldNames['title']]) ?
		$_POST[$contentFieldNames['title']] : NULL;
	
		$contentObj->seq = isset($_POST[$contentFieldNames['sequence']]) ?
		$_POST[$contentFieldNames['sequence']] : 0;
	
		$contentObj->status = isset($_POST[$contentFieldNames['status']]) ?
		$_POST[$contentFieldNames['status']] : NULL;
	
		$contentObj->target = isset($_POST[$contentFieldNames['target']]) ?
		$_POST[$contentFieldNames['target']] : NULL;
	
		if( isset($_POST['isBlank'])) {
			$contentObj->target = "_blank";
		}
	
		$contentObj->shortDescription = isset($_POST[$contentFieldNames['description']]) ?
		$_POST[$contentFieldNames['description']] : NULL;
	
		$contentObj->ownerId = isset($_POST[$contentFieldNames['owner']]) ?
		$_POST[$contentFieldNames['owner']] : NULL;
	
		$contentObj->pageType = isset($_POST[$contentFieldNames['page-type']]) ?
		$_POST[$contentFieldNames['page-type']] : NULL;
	
		$contentObj->pageArgument = isset($_POST[$contentFieldNames['page-argument']]) ?
		$_POST[$contentFieldNames['page-argument']] : NULL;
		
		$contentObj->galleryImage = isset($_POST[$contentFieldNames['gallery-image']]) ?
		$_POST[$contentFieldNames['gallery-image']] : NULL;
	
		$contentObj->articleFile = isset($_POST[$contentFieldNames['article-file']]) ?
		$_POST[$contentFieldNames['article-file']] : NULL;
		/*
		$contentObj->articleURL = isset($_POST[$contentFieldNames['article-url']]) ?
		$_POST[$contentFieldNames['article-url']] : NULL;
		*/
		
		$contentObj->articleImage = isset($_POST[$contentFieldNames['article-image']]) ?
		$_POST[$contentFieldNames['article-image']] : NULL;
		
		$contentObj->articleDescription = isset($_POST[$contentFieldNames['article-description']]) ?
		$_POST[$contentFieldNames['article-description']] : NULL;

		$contentObj->ogType = isset($_POST[$contentFieldNames['og-type']]) ?
		$_POST[$contentFieldNames['og-type']] : NULL;

		$contentObj->authorFullName = isset($_POST[$contentFieldNames['author-name']]) ?
		$_POST[$contentFieldNames['author-name']] : NULL;

		$contentObj->authorLink = isset($_POST[$contentFieldNames['author-link']]) ?
		$_POST[$contentFieldNames['author-link']] : NULL;
	
		$contentObj->dateModified = date("Y-m-d H:i:s");
		
		//die($this->dump());
	}
	
	/*---------------------------------------------------------------------------------------------
	 * 
	 */
	public function dump() {
		return	"ID: " . $this->ID . "<br/>" .
				"guid: " . $this->guid . "<br/>" .
				"lang: " . $this->lang . "<br/>" .
				"defaultParentId: " . $this->defaultParentId . "<br/>" .
				"permalink: " . $this->permalink . "<br/>" .
				"title: " . $this->title . "<br/>" .
				"status: " . $this->status . "<br/>" .
				"target: " . $this->target . "<br/>" .
				"shortDescription: " . $this->shortDescription . "<br/>" .
				"creatorId: " . $this->creatorId . "<br/>" .
				"ownerId:" . $this->ownerId . "<br/>" .
				"ownerType: " . $this->ownerType . "<br/>" .
				"pageType:" . $this->pageType . "<br/>" .
				"canEdit:" . $this->canEdit . "<br/>" .
				"hasRightbar:" . $this->hasRightbar . "<br/>" .
				"pageArgument:" . $this->pageArgument . "<br/>" .
				"galleryImage: " . $this->galleryImage . "<br/>" .
				"articleFile: " . $this->articleFile . "<br/>" .
				"articleImage: " . $this->articleImage . "<br/>" .
				"articleDescription: " . $this->articleDescription . "<br/>" .
				"ogType: " . $this->ogType . "<br/>" .
				"authorFullName: " . $this->authorFullName . "<br/>" .
				"authorLink: " . $this->authorLink . "<br/>" .
				"dateCreated: " . $this->dateCreated . "<br/>" .
				"dateModified: " . $this->dateModified . "<br/>" .
				
				"<br/>/parent query ****** <br/><br/>" .
				
				"parentId: " . $this->parentId . "<br/>" .
				"grandParentId: " . $this->grandParentId . "<br/>" .
				"parentPermalink: " . $this->parentPermalink . "<br/>" .
				"parentTitle: " . $this->parentTitle . "<br/>" .
				"parentPageType: " . $this->parentPageType . "<br/>" .
				
				"<br/>/owner query ****** <br/><br/>" .
				
				"ownerName: " . $this->ownerName . "<br/>" .
				"ownerImage: " . $this->ownerImage . "<br/>" .
				"ownerBio: " . $this->ownerBio . "<br/>" .
				
				"<br/>/directories ****** <br/><br/>" .

				"sourceDir: " . $this->sourceDir . "<br/>" .
				"themeDir: " . $this->themeDir . "<br/>" .
				"contentDir: " . $this->contentDir . "<br/>";
		}
	
}
?>