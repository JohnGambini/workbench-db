<?php
/*--------------------------------------------------------------------------------------------
 * wbSql.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
class wbSql
{
	public $sqlContentMenu = NULL;
	public $sqlMenuList = NULL;
	public $sqlMenuGroups = NULL;
	public $sqlSideBarMenuList = NULL;
	public $sqlPageItems = NULL;
	//public $sqlGalleryItems = NULL;
	//public $sqlRightbarItems = NULL;
	//public $sqlArticleItems = NULL;
	public $sqlListPosts = NULL;
	public $sqlUsers = NULL;
	public $sqluserGroups = NULL;
	public $sqlUserGroupList = NULL;
	public $sqlPageTypes = NULL;
	public $sqlContent = NULL;
	public $sqlTabItems = NULL;
	public $sqlStatusList = NULL;
	public $sqlArrays = NULL;
	
	function wbSql( dbUser $userObj, dbContent $contentObj ) {
		
		/*-------------------------------------------------------------------------
		 * Here's the sql for the root content menu, joined with menu_items for item sequence
		 */
		
		$this->sqlContentMenu = "select vw_contentList.contentId, vw_contentList.title, vw_contentList.permalink, vw_contentList.sequence, " .
			"vw_contentList.articleImage, vw_contentList.authorFullName authorName, vw_contentList.authorLink " .
			"from wb_content, vw_contentList " .
			"where wb_content.ID = vw_contentList.menuId " .
			"and vw_contentList.menuType = '1' " .
			"and wb_content.lang like '" . $contentObj->lang . "%' and wb_content.permalink = '/" . substr($contentObj->lang,0,2) . "' " . 
			"and ((vw_contentList.ownerId = '" . $userObj->ID . "' and (vw_contentList.status = 'Private' or vw_contentList.status = 'Draft')) or vw_contentList.status in (". $userObj->groups()  .  ") or vw_contentList.status = 'Public') " .
			"order by sequence";
		
		/*----------------------------------------------------------------------------
		 * this is the list of menus and menuitems
		 * one row for each menu item. Used in the manage menus dialog
		 */
		$this->sqlMenuList = "select vw_menulist.m_permalink menuPermalink, wb_menuitems.ID, " .
				"wb_menuitems.menuId, wb_menuitems.menuType, " .
				"wb_menuitems.contentId, menuTitle, menuDescription, m_ownerId, sequence, title, ownerId, " .
				"wb_content.permalink " .
				"from vw_menulist left join wb_menuitems on vw_menulist.menuId = wb_menuitems.menuId " .
				"left join wb_content on wb_menuitems.contentId = wb_content.ID " .
				"where vw_menulist.m_lang = '" . $contentObj->lang . "' " .
				"and ((vw_menulist.m_ownerId = '" . $userObj->ID . "' and (wb_content.status = 'Private' or wb_content.status = 'Draft')) or wb_content.status in (". $userObj->groups()  .  ") or wb_content.status = 'Public') " .
				"order by menuTitle, menuType, sequence";
		
		/*-------------------------------------------------------------------------------
		 * this is a list of menu groups, and menus, with some content columns for the menu groups scroll tables
		 */
		$this->sqlMenuGroups = "select wb_menugroups.ID, parentId, contentId, vw_menulist.m_permalink, 
				shortDescription, mgseq, menuTitle, menuDescription " .
				"from wb_menugroups, vw_menulist, vw_content " .
				"where wb_menugroups.contentId = vw_content.ID " .
				"and wb_menugroups.menuId = vw_menulist.menuId " .
				"and vw_menulist.m_lang = '" . $contentObj->lang . "' " .
				"and (( ownerId = '" . $userObj->ID . "' and (vw_menulist.m_status = 'Private' or vw_menulist.m_status = 'Draft')) or vw_menulist.m_status in (" . $userObj->groups() . ") or vw_menulist.m_status = 'Public' ) " .
				"order by shortDescription, mgseq";
		
		/*-------------------------------------------------------------------------------
		 * this is a list of menu groups, menus, and menu items for the sidebar menu widget
		 */
		$this->sqlSideBarMenuList = "select wb_menugroups.parentId, wb_menugroups.contentId, 
				trackParent, mgseq, wb_menugroups.menuId, " .
				"vw_menulist.m_parentId, vw_menulist.menuTitle, vw_menulist.m_permalink, " .
				"wb_menuitems.sequence seq, " .
				"wb_content.ID contentId, wb_content.title, wb_content.permalink, " . 
				"wb_content.status, " . 
				"wb_content.target, " .
				"wb_content.galleryImage " .
				
				"from wb_menugroups, vw_menulist left join wb_menuItems on vw_menulist.menuId = wb_menuitems.menuId " .
				"left join wb_content on wb_menuitems.contentId = wb_content.ID " .
				"and wb_menuitems.menuType = '1' " .
				"and (( ownerId = '" . $userObj->ID . "' and (wb_content.status = 'Private' or wb_content.status = 'Draft')) or wb_content.status in (" . $userObj->groups() . ") or wb_content.status = 'Public' ) " .
				"where wb_menugroups.menuId = vw_menulist.menuId " .
				"and vw_menulist.m_lang = '" . $contentObj->lang . "' " .
				"and wb_menugroups.parentId = '" . $contentObj->parentId . "' " .
				"and wb_menugroups.contentId = '" . $contentObj->ID . "' " .
				"order by mgseq, menuId, sequence";
		
				/*
				"from wb_menugroups, vw_menulist, wb_menuitems, wb_content " .
				"where wb_menugroups.menuId = vw_menulist.ID and vw_menulist.ID = wb_menuitems.menuId " .
				"and wb_menuitems.contentId = wb_content.ID " .
				"and wb_menugroups.parentId = '" . $contentObj->parentId . "' " .
				"and wb_menugroups.contentId = '" . $contentObj->ID . "' " .
				"and ( ownerId = '" . $userObj->ID . "' or status = 'published' ) " .
				"order by mgseq, menuId, sequence"; 
	            */	

		/*---------------------------------------------------------------------------------------
		 * This lists one menu, and the content associated with the menu's items. 
		 * This is used by the load_pageItems function. The load_pageItems function is the
		 * combined load_galleryItems, load_rightbarItems, and load_articles functions. 
		 */
		$this->sqlPageItems = "select menuType, contentId, itemId, permalink, title, sequence, target, " .
				"galleryImage, pageType, articleDescription, articleImage, ogType " .
				"from vw_contentlist " .
				"where menuId = '" . $contentObj->ID . "' " .
				"and (( ownerId = '" . $userObj->ID . "' and (status = 'Private' or status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) order by sequence";
		
		/*---------------------------------------------------------------------------------------
		 * This lists one menu, and the content associated with the
		 * menu's items. Used by the gallery page
		 */
		$this->sqlGalleryItems = "select contentId, itemId, permalink, title, sequence, target, " .
				"galleryImage, pageType, articleDescription, articleImage, ogType " .
				"from vw_contentlist " .
				"where menuId = '" . $contentObj->ID . "' " .
				"and menuType = '1' " .
				"and (( ownerId = '" . $userObj->ID . "' and (status = 'Private' or status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) order by sequence";
		/*------------------------------------------------------------------------------------
		 * select the rightbar items
		 */
		$this->sqlRightbarItems = "select contentId, itemId, permalink, title, sequence, target, " .
				"galleryImage, pageType, articleDescription, articleImage, ogType " .
				"from vw_contentlist " .
				"where menuId = '" . $contentObj->ID . "' " .
				"and menuType = '2' " .
				"and (( ownerId = '" . $userObj->ID . "' and (status = 'Private' or status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) order by sequence";
		/*---------------------------------------------------------------------------------------
		 * This lists one menu, and the content associated with the
		 * menu's items. Used by the articles page
		 */
		$this->sqlArticlesList = "select contentId, itemId, permalink, title, sequence, target, " .
				"galleryImage, pageType, articleDescription, articleImage, ogType " .
				"from vw_contentlist " .
				"where menuId = '" . $contentObj->ID . "' " .
				"and menuType = '3' " .
				"and (( ownerId = '" . $userObj->ID . "' and (status = 'Private' or status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) order by sequence";
		/*-------------------------------------------------------------------------
		 * Here's the sql for the parallax view, joined with menu_items for item sequence
		 */
		
		$this->sqlParallaxList = "select vw_contentList.contentId, wb_content.title menuTitle, " .
				"wb_content.permalink menuLink, vw_contentList.title, vw_contentList.permalink, " .
				"vw_contentList.sequence, vw_contentList.galleryImage, vw_contentList.articleImage, " .
				"vw_contentList.authorFullName authorName, vw_contentList.authorLink " .
				"from wb_content, vw_contentList " .
				"where wb_content.ID = vw_contentList.menuId " .
				"and vw_contentList.menuType = '1'" .
				"and wb_content.lang like '" . $contentObj->lang . "%' and wb_content.title like '" . $contentObj->pageArgument . "%' " .
				"and ((vw_contentList.ownerId = '" . $userObj->ID . "' and (vw_contentList.status = 'Private' or vw_contentList.status = 'Draft')) or vw_contentList.status in (". $userObj->groups()  .  ") or vw_contentList.status = 'Public') " .
				"order by sequence";
		
		/*------------------------------------------------------------------------------------
		 * List of posts
		 */
		$this->sqlListPosts = "select vw_content.ID, defaultParentId, title, status, shortDescription, permalink, ownerId, ownerFullName, vw_content.dateModified, SUM(byteCount) byteCount " .
			"from vw_content, wb_articles " .
			"where vw_content.ID = wb_articles.contentId and pageType in ('article','profile','image') " .
			"and lang = '" . $contentObj->lang . "' and (( ownerId = '" . $userObj->ID . "' and (status = 'Private' or status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) " .
			"group by vw_content.ID, defaultParentId, title, status, permalink, ownerId, ownerFullName, vw_content.dateModified " .
			"order by title";
		
		/*----------------------------------------------------------------------------------
		 * Here's the select for the users table.
		 */
		$this->sqlUsers = "select ID, username, fullName, type, permalink from vw_user order by fullName";
	
		/*-----------------------------------------------------------------------------------
		 * select user groups
		 */
		/*$this->sqlUserGroups = "select ID, name from wb_usergroups where lang = '" . substr($contentObj->lang,0,2) . "' order by name";*/
		$this->sqlUserGroups = "select ID, name from wb_usergroups where lang = '" . $contentObj->lang . "' order by name";
		
		/*----------------------------------------------------------------------------------
		 * UserGroupsList;
		 */
		$this->sqlUserGroupList = "select wb_grouplists.ID, wb_usergroups.name, shortDescription, wb_user.fullname " .
				"from wb_usergroups left join wb_grouplists on wb_usergroups.ID = wb_grouplists.groupId left join wb_user " .
				"on wb_grouplists.userId = wb_user.ID where lang = '" . $contentObj->lang . "' order by name, fullname";
		
		/*----------------------------------------------------------------------------------
		 * Here's the select for the page types table
		 */
		$this->sqlPageTypes = "select ID, pageTypeName from wb_pagetypes " .
			"where seq > 0 and userSelect <='" . $userObj->type . "' order by seq";
		
		/*----------------------------------------------------------------------------------
		 * This is the sql for the parents list
		 */
		$sqlParents = "select ID, permalink from wb_content where lang = '" .
				$contentObj->lang . "' and ( ownerId = '" . $userObj->ID .
				"' or status = 'Public') order by permalink";
		
		/*----------------------------------------------------------------------------------
		 * This is the original sql for the menus list but the sql further down will
		 * be used to load the parents, menus, and contents arrays
		 */
		$sqlMenus = "select ID, defaultParentId, permalink, title, shortDescription, pageTypeId from wb_content where lang = '" .
				$contentObj->lang . "' and ID <> 0 order by title";
		
		/*-----------------------------------------------------------------------------------
		 *   This is the original sql for the content list but the sql further down will
		 *   be used to load the parent, menus, and content arrays. 
		 */
		$this->sqlContent = "select ID, shortDescription from wb_content " .
				"where ID <> 0 and lang = '" . $contentObj->lang . "' " .
				"order by shortDescription";

		/*------------------------------------------------------------------------------------
		 * Load the tabs for article pages
		 */
		$this->sqlTabItems = "select wb_articles.ID, tabTitle, articleText, wb_articles.articleImage, wb_articles.dateModified, sequence, title, galleryImage, permalink, status, ownerId " . 
		"from wb_articles, wb_content " .
		"where contentId = wb_content.ID " .
		"and (( ownerId = '" . $userObj->ID . "' and (wb_content.status = 'Private' or wb_content.status = 'Draft')) or status in (" . $userObj->groups() . ") or status = 'Public' ) ";
		
		/*------------------------------------------------------------------------------------
		 * Load the user groups to be used for status array
		 */
		$this->sqlStatusList = "select name " .
				"from wb_usergroups " .
				"where name in (" . $userObj->groups() . ") and lang = '" . $contentObj->lang . "' order by name";
		
		/*----------------------------------------------------------------------------------
		 * This loads the parents and menus, and contents arrays with one query. I'm keeping the
		 * sql for the parents, menus, and content arrays though just for a check.
		 */
		$this->sqlArrays = "select ID, defaultParentId, permalink, title, shortDescription, pageType from wb_content where lang = '" .
				$contentObj->lang . "' " .
				"and ( ownerId = '" . $userObj->ID . "' or status in (" . $userObj->groups() . ") or status = 'Public' ) " .
				"order by shortDescription";
	}
}?>