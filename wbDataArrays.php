<?php
/*--------------------------------------------------------------------------------------------
 * wbDataArrays.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
class wbDataArrays
{
	//vw_content attributes
	public $parentsArray = NULL;
	public $menusArray = NULL;
	public $menusDescArray = NULL;
	public $usersArray = NULL;
	public $userGroupsArray = NULL;
	public $pageTypesArray = NULL;
	private $galleryItemsArray = NULL;
	private $rightbarItemsArray = NULL;
	private $articleItemsArray = NULL;
	private $pageItemsArray_4 = NULL;
	private $featuredItemsArray = NULL;
	public $parallaxItemsArray = NULL;
	public $sidebarMenusArray = NULL;
	public $rootContentArray = NULL;
	public $statusArray = NULL;
	public $langArray = NULL;
	public $contentArray = NULL;
	public $targetArray = NULL;
	public $tabsArray = NULL;
	public $themesArray = NULL;
	public $imageArray = NULL;	//background image for front-page_content.php
	
	function wbDataArrays() {
		
		$this->targetArray = array('_self' => '_self', '_blank' => '_blank');
		$this->langArray = array('en','fr'/*, 'de', 'it'*/);
		
		$this->themesArray = array('default' => 'default');
		$this->themesArray['utere-verbis'] = 'Utere Verbis';
		$this->themesArray['calliopesutra-1.0'] = 'Calliope Sutra 1.0';
		$this->themesArray['calliopesutra-2.0'] = 'Calliope Sutra 2.0';
		$this->themesArray['calliopesutra-3.0'] = 'Calliope Sutra 3.0';
		$this->themesArray['taylorson'] = 'Taylorson';
		$this->themesArray['pdoTempTheme'] = 'pdoTempTheme';
		
		$this->imageArray = array('/BryceLiquidMetal.jpg' => 'Liquid Metal');
		$this->imageArray['/BryceNightSky.jpg'] = 'Night Sky';
		$this->imageArray['/BryceSkyGrass1.jpg'] = 'Lake, Sky and Grass';
		$this->imageArray['/FireFlyTrails.jpg'] = 'Fire Flies';
		$this->imageArray['/Hubble-2.jpg'] = 'Hubble Image';
		$this->imageArray['/HubbleSombrero.jpg'] = 'Hubble Sombrero';
		$this->imageArray['/TerragenDesert.jpg'] = 'Desert';
		$this->imageArray['/TerragenEarlyRise.jpg'] = 'Early Rise';
		$this->imageArray['/TerragenDaybreakjpg'] = 'TerragenDaybreak';
		$this->imageArray['/theSacrifice.jpg'] = 'The Sacrifice';
		$this->imageArray['/train.png'] = 'Train';
		
	}

	/*------------------------------------------------------------------------
	 * 
	 */
	function & get_galleryItemsArray(wbDatabase $dbObj, wbSql $sqlObject) {
		global $debugMessage;
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_galleryItemsArray() was called<br/>";
		if($this->galleryItemsArray === NULL)
			$this->load_pageItems($dbObj, $sqlObject);
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "there are " . count($this->galleryItemsArray) . " gallery items.<br/>";
		return $this->galleryItemsArray;
	}
	
	/*---------------------------------------------------------------------------
	 *
	 */
	function & get_rightbarItemsArray(wbDatabase $dbObj, wbSql $sqlObject) {
		global $debugMessage;
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_rightbarItemsArray() was called<br/>";
		if($this->rightbarItemsArray === NULL)
			$this->load_pageItems($dbObj, $sqlObject);
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "there are " . count($this->rightbarItemsArray) . " rightbar items.<br/>";
		return $this->rightbarItemsArray;
	}

	/*------------------------------------------------------------------------
	 *
	 */
	function & get_articleItemsArray(wbDatabase $dbObj, wbSql $sqlObject) {
	global $debugMessage;
	if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_articleItemsArray() was called<br/>";
	if($this->articleItemsArray === NULL)
		$this->load_pageItems($dbObj, $sqlObject);
	if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "there are " . count($this->articleItemsArray) . " article items.<br/>";
	return $this->articleItemsArray;
	}
	
	/*------------------------------------------------------------------------
	 *
	 */
	function & get_pageItems4Array(wbDatabase $dbObj, wbSql $sqlObject) {
		global $debugMessage;
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_pageItems4Array() was called<br/>";
		if($this->pageItemsArray_4 === NULL)
			$this->load_pageItems($dbObj, $sqlObject);
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "there are " . count($this->pageItemsArray_4) . " pageItems_4 items.<br/>";
		return $this->pageItemsArray_4;
	}
	
	/*------------------------------------------------------------------------
	 *
	 */
	function & get_featuredItemsArray(dbContent $contentObj ) {
		global $debugMessage;
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "get_featuredItemsArray() was called<br/>";
		if($this->featuredItemsArray === NULL)
			$this->load_featuredItemsArray($contentObj);
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . "there are " . count($this->featuredItemsArray) . " featured items.<br/>";
		return $this->featuredItemsArray;
	}

	/*------------------------------------------------------------------------
	 *
	 */
	function get_parallaxItemsArray($dbObj, $sqlObject) {
		if($this->parallaxItemsArray == NULL)
			return $this->load_parallaxItems($dbObj, $sqlObject);
			return true;
	}
	
	/*-------------------------------------------------------------------------
	 * 
	 */
	function get_sidebarMenusArray($dbObj, $sqlObject) {
		if($this->sidebarMenusArray == NULL)
			return $this->load_sidebarMenus($dbObj, $sqlObject);
			return true;
		
	}

	/*-------------------------------------------------------------------------
	 *
	 */
	function get_rootContentArray($dbObj, $sqlObject) {
		if($this->rootContentArray == NULL)
			return $this->load_rootContent($dbObj, $sqlObject);
			return true;
	
	}
	/*---------------------------------------------------------------------------
	 * 
	 */
	function get_tabsArray($dbObj, $sqlObject, $contentId) {
		if($this->tabsArray == NULL)
			return $this->load_tabItems($dbObj, $sqlObject, $contentId);
		return true;
	}
	
	/*---------------------------------------------------------------------------
	 *
	 */
	function get_userGroupsArray($dbObj, $sqlObject ) {
		if($this->userGroupsArray == NULL)
			return $this->load_userGroupsItems($dbObj, $sqlObject);
		return true;
	}
	
	
	/*---------------------------------------------------------------------------
	 * 
	 */
	function getDialogArrays($dbObj, $contentObj, $sqlObject ) {
		if($this->menusArray == NULL)
			return $this->loadDialogArrays($dbObj, $contentObj, $sqlObject);
		return true;
	}

	/*-----------------------------------------------------------------------------
	 * load_pageItems
	 */
	function load_pageItems(wbDatabase $dbObj, wbSql $sqlObject) {

		global $debugMessage;
		
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . '<span style="color:red">called load_pageItems()</span><br/>';
		
		if( ! $dbObj->query($sqlObject->sqlPageItems)) {
			$dbObj->error = "wbDataArrays: load_pageItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlPageItems;
					return false;
		}
		
		if($this->galleryItemsArray == NULL)
			$this->galleryItemsArray = array();
		if($this->rightbarItemsArray == NULL)
			$this->rightbarItemsArray = array();
		if($this->articleItemsArray == NULL)
			$this->articleItemsArray = array();
		if($this->pageItemsArray_4 == NULL)
			$this->pageItemsArray_4 = array();
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			if($row['menuType'] == 1)
				$this->addPageItem($this->galleryItemsArray, $row);
			else if($row['menuType'] == 2) 
				$this->addPageItem($this->rightbarItemsArray, $row);
			else if($row['menuType'] == 3)
				$this->addPageItem($this->articleItemsArray, $row);
			else
				$this->addPageItem($this->pageItemsArray_4, $row);
		}
	
		return true;
	}


	/*-----------------------------------------------------------------------------
	 * addPageItem
	 */
	function addPageItem(array & $subject, array $row) {
		
		$subject[] = array('ID' => $row['contentId'],
				'itemId' => $row['itemId'],
				'permalink' => $row['permalink'],
				'title' => $row['title'],
				'sequence' => $row['sequence'],
				'target' => $row['target'],
				'galleryImage' => $row['galleryImage'],
				'pageType' => $row['pageType'],
				'articleDescription' => $row['articleDescription'],
				'articleImage' => $row['articleImage'],
				'type' => $row['ogType']
		);
	}
	
	/*-----------------------------------------------------------------------------
	 * load_featuredArticles
	 */
	function load_featuredItemsArray(dbContent $contentObj) {
		global $debugMessage; 
		if(DEBUG_VERBOSE) $debugMessage = $debugMessage . '<span style="color:red">called load_featuredItemsArray()</span><br/>';
		$this->featuredItemsArray = array();
		$filename = ABSDIR . "\\featured." . substr($contentObj->lang,0,2) . ".ini";
		//die($filename);
		if(file_exists($filename)) {
			$this->featuredItemsArray = parse_ini_file($filename,TRUE);
			//added indexes so this array will work with articles and gallery widgets
			foreach($this->featuredItemsArray as $key => $value) {
				$this->featuredItemsArray[$key]['permalink'] = $value['url'];
				$this->featuredItemsArray[$key]['galleryImage'] = $value['image'];
				$this->featuredItemsArray[$key]['articleDescription'] = $value['description'];
			}
		}
	}
	
	
	/*-----------------------------------------------------------------------------
	 * load_galleryItems
	 */

	function load_galleryItems(wbDatabase $dbObj, wbSql $sqlObject) {
		if( ! $dbObj->query($sqlObject->sqlGalleryItems)) {
			$dbObj->error = "wbDataArrays: load_galleryItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlGalleryItems;
			return false;
		}

		$this->galleryItemsArray = array();
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->galleryItemsArray[$i] = array('ID' => $row['contentId'], 
					'itemId' => $row['itemId'],
					'permalink' => $row['permalink'], 
					'title' => $row['title'], 
					'sequence' => $row['sequence'], 
					'target' => $row['target'],
					'galleryImage' => $row['galleryImage'], 
					'pageType' => $row['pageType'],
					'articleDescription' => $row['articleDescription'],
					'articleImage' => $row['articleImage'],
					'type' => $row['ogType']
			);
		}
		
		return true;
	}

	/*-------------------------------------------------------------------------------------
	 * load_rightbarItems
	 */

	function load_rightbarItems(wbDatabase $dbObj, wbSql $sqlObject) {
	
		if( ! $dbObj->query($sqlObject->sqlRightbarItems )) {
			$dbObj->error = "wbDataArrays: load_rightbarItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlRightbarItems;
					return false;
		}
	
		for( $i=0; $row = mysqli_fetch_array($dbObj->result); $i++) {
			$this->rightbarItemsArray[$i] = array('ID' => $row['contentId'],
					'itemId' => $row['itemId'],
					'permalink' => $row['permalink'],
					'title' => $row['title'],
					'sequence' => $row['sequence'],
					'target' => $row['target'],
					'galleryImage' => $row['galleryImage'],
					'pageType' => $row['pageType'],
					'articleDescription' => $row['articleDescription'],
					'articleImage' => $row['articleImage'],
					'type' => $row['ogType']
			);
		}
	
		return true;
	}

	/*-----------------------------------------------------------------------------
	 * load_articleItems
	 */

	function load_articleItems(wbDatabase $dbObj, wbSql $sqlObject) {
		if( ! $dbObj->query($sqlObject->sqlArticlesList)) {
			$dbObj->error = "wbDataArrays: load_articleItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlArticlesList;
					return false;
		}
	
		$this->articleItemsArray = array();
	
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->articleItemsArray[$i] = array('ID' => $row['contentId'],
					'itemId' => $row['itemId'], 
					'permalink' => $row['permalink'],
					'title' => $row['title'],
					'sequence' => $row['sequence'],
					'target' => $row['target'],
					'galleryImage' => $row['galleryImage'],
					'pageType' => $row['pageType'],
					'articleDescription' => $row['articleDescription'],
					'articleImage' => $row['articleImage'],
					'type' => $row['ogType']
			);
		}
	
		return true;
	}
	
	/*-----------------------------------------------------------------------------
	 * load_parallaxItems
	 */
	function load_parallaxItems($dbObj, $sqlObject) {
		if( ! $dbObj->query($sqlObject->sqlParallaxList)) {
			$dbObj->error = "wbDataArrays: load_parappaxItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlParallaxList;
					return false;
		}
	
		$this->parallaxItemsArray = array();
	
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->parallaxItemsArray[$row['contentId']] = array(
					'menuTitle' => $row['menuTitle'],
					'menuLink' => $row['menuLink'],
					'title' => $row['title'],
					'permalink' => $row['permalink'],
					'galleryImage' => $row['galleryImage'],
					'sequence' => $row['sequence'],
					'articleImage' => $row['articleImage'],
					'authorName' => $row['authorName'],
					'authorLink' => $row['authorLink']
			);
		}
	
		return true;
	}
	
	/*-------------------------------------------------------------------------------------
	 * load_sidebarMenus
	 */

	function load_sidebarMenus($dbObj, $sqlObject) {
		if( ! $dbObj->query($sqlObject->sqlSideBarMenuList)) {
			$dbObj->error = "wbDataArrays: load_sidebarMenus: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlSideBarMenuList;
					return false;
		}
		
		$this->sidebarMenusArray = array();
		
		$currentId = -1;
		$currentSubArray = NULL;
		for( $i = 1; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			if($row['menuId'] != $currentId) {
		
				$currentId = $row['menuId'];
		
				$this->sidebarMenusArray[$currentId] = array (
						'mgseq' => $row['mgseq'],
						'menuParentId' => $row['m_parentId'],
						'menuTitle' => $row['menuTitle'],
						'menuPermalink' => $row['m_permalink'],
						'menuItems' => array()
				);
		
				$currentSubArray = &$this->sidebarMenusArray[$currentId]['menuItems'];
			}
		
			if(isset($row['title'])) {
				$currentSubArray[$row['contentId']] = array (
						'title' => $row['title'],
						'permalink' => $row['permalink'],
						'galleryImage' => $row['galleryImage']
				);
			}
		}
		
	}

	/*-----------------------------------------------------------------------------
	 * load_rootContent
	 */
	function load_rootContent($dbObj, $sqlObject) {
		if( ! $dbObj->query($sqlObject->sqlContentMenu)) {
			$dbObj->error = "wbDataArrays: load_rootContent: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlContentMenu;
			return false;
		}
	
		$this->rootContentArray = array();
	
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->rootContentArray[$row['contentId']] = array(
				'title' => $row['title'], 
				'permalink' => $row['permalink'], 
				'sequence' => $row['sequence'],
				'articleImage' => $row['articleImage'],
				'authorName' => $row['authorName'],
				'authorLink' => $row['authorLink']
			);
		}
	
		return true;
	}
	
	/*-------------------------------------------------------------------------------------
	 * load_tabItems
	 */
	function load_tabItems($dbObj, $sqlObject, $contentId) {
		
		$contentClause = " and contentId = -1";
		if(isset($contentId) and strlen($contentId) > 0)
			$contentClause = " and contentId = " . $contentId;
		
		if( ! $dbObj->query($sqlObject->sqlTabItems . $contentClause . " order by sequence" )) {
			$dbObj->error = "wbDataArrays: load_tabItems: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlTabItems . $contentClause;
			return false;
		}
		
		$this->tabsArray = array();

		for( $i=0; $row = mysqli_fetch_array($dbObj->result); $i++) {
			$this->tabsArray[$row['tabTitle']] = 
				array(
					'articleText' => $row['articleText'],
					'articleImage' => $row['articleImage'],
					'dateModified' => $row['dateModified'],
					'ID' => $row['ID'],	
					'sequence' => $row['sequence'],
					'galleryImage' => $row['galleryImage'], 
					'permalink' => $row['permalink'],
					'title' => $row['title']
				);
		}
		
		return true;
	}

	
	/*------------------------------------------------------------------------------------
	 *
	 */
	function load_userGroupsItems($dbObj, $sqlObject) {
		
		if( ! $dbObj->query($sqlObject->sqlUserGroups)) {
			$dbObj->error = "load_userGroupsArray: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlUserGroups;
					return false;
		}
		
		$this->userGroupsArray = array();
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->userGroupsArray[$row['ID']] = $row['name'];
		}
	}
	
	/*------------------------------------------------------------------------------------
	 * 
	 */
	function loadDialogArrays($dbObj, $contentObj, $sqlObject ) {
		
		$this->menusArray = array( 0 => 'none');
		
		$this->menusDescArray = array( 0 => 'none');

		$this->parentsArray = array( 0 => 'none');
		
		$this->contentArray = array( 0 => 'none');

		$menuTypes = array();
		$sqlQuery = 'select pageTypeName from wb_pagetypes where isMenu = TRUE';
		if($dbObj->query($sqlQuery))
		{
			for($i = 1; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
				array_push($menuTypes,$row['pageTypeName']);
			}
		}
		
		$parentTypes = array('default' => 'default');
		$sqlQuery = 'select pageTypeName from wb_pagetypes where isParent = TRUE';
		if($dbObj->query($sqlQuery)){
			for($i = 1; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
				array_push($parentTypes,$row['pageTypeName']);
			}
		}
		
		$tmpString = $dbObj->error;
		if( ! $dbObj->query($sqlObject->sqlArrays)) {
			$dbObj->error = $tmpString . "wbDataArrays:loadDialogArrays: An sql error occured<br><br>" . 
			$dbObj->error . "<br/><br/>" . $sqlObject->sqlArrays . "<br/><br/>";
			return false;
		}
		
		$p = 1;
		$m = 1;
		$c = 1;
		for($i = 1; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			if( in_array($row['pageType'],$menuTypes) and strlen($row['title'])) {
				$this->menusArray[$row['ID']] = $row['title'];
				$this->menusDescArray[$row['ID']] = $row['shortDescription'];
			}
			if( in_array($row['pageType'], $parentTypes) and $row['defaultParentId'] <> -1 ) {
				$this->parentsArray[$row['ID']] = $row['permalink'];
			}
			if( $row['defaultParentId'] <> -1 ) {
				$this->contentArray[$row['ID']] = $row['shortDescription'];
			}
		}

		asort($this->parentsArray);
		//asort($this->contentArray);
		//asort($this->menusArray);
		
		/*----------------------------------------------------------------------------------
		 * Load Users Array
		 */
		if( ! $dbObj->query($sqlObject->sqlUsers)) {
			$dbObj->error = "load_usersArray: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
				$sqlObject->sqlUsers;
			return false;
		}
		
		$this->usersArray = array();
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->usersArray[$row['ID']] = $row['fullName'];
		}
		
		/*-----------------------------------------------------------------------------
		 * Load Pages array
		 * 
		 */
		if( ! $dbObj->query($sqlObject->sqlPageTypes)) {
			$dbObj->error = "load_pagesArray: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
				$sqlObject->sqlPageTypes;
			return false;
		}
		
		$this->pageTypesArray = array();
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->pageTypesArray[$row['pageTypeName']] = $row['pageTypeName'];
		}

		/*----------------------------------------------------------------------------------
		 * Load status array
		 * 
		 * 
		 */
		
		if( ! $dbObj->query($sqlObject->sqlStatusList)) {
			$dbObj->error = "load_statusArray: An sql error occured<br><br>" . $dbObj->error . "<br><br>" .
					$sqlObject->sqlStatusList;
					return false;
		}
		
		$this->statusArray = array ('Draft'=> 'Draft', 'Public' => 'Public', 'Private' => 'Private');
		
		for($i = 0; $row = mysqli_fetch_array($dbObj->result); $i++ ) {
			$this->statusArray[$row['name']] = $row['name'];
		}
		
	}

}
?>