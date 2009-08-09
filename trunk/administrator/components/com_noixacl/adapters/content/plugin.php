<?php
/**
 * No Direct Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PluginContent extends Adapters
{
    function administrator(){
        $db = & JFactory::getDBO();

        //get id from content
        $cid			= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$id				= JRequest::getVar( 'id', $cid[0], '', 'int' );
		$catid				= JRequest::getVar( 'catid', 0, '', 'int' );

		if ($catid==0) {
			$sqlContent = "SELECT catid FROM #__content WHERE id = {$id}";
			$db->setQuery( $sqlContent );
			$catid = $db->loadResult();
		}

		// MIKE: Change task if it's apply or cancel, preview and orderdown/up
		$task = JRequest::getCMD('task');

		switch($task) {
			case 'apply':
				$task= 'save';
				break;
			case 'cancel':
				$catid = '';
				$task= 'edit';
			case 'preview':
				$task= 'edit';
				break;
			case 'orderup':
			case 'orderdown':
				$task= 'saveorder';
				break;
			case 'accesspublic':
			case 'accessregistered':
			case 'accessspecial':
				$task= 'accesslevel';
				break;
		}
        $result = array(
            'task' => $task,
            'params' => array(
                '$catid' => $catid
            )
        );

		// MIKE: Old code below
//        $result = array(
//            'task' => $task,
//            'params' => array(
//                '$catid' => $catid
//            )
//        );

        return $result;
    }

	function site(){
        $db = & JFactory::getDBO();

        //get id from content
        $cid			= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$id				= JRequest::getVar( 'id', $cid[0], '', 'int' );
		$task = JRequest::getCMD('task');
        $view = JRequest::getCMD('view');

//rrr
//        $sqlContent = "SELECT catid FROM #__content WHERE id = {$id}";
        $sqlContent = "SELECT cont.catid catid, cont.access contaccess, cat.access cataccess FROM #__content cont" .
			" left join #__categories cat on cont.catid=cat.id" .
			" WHERE cont.id = {$id}";
		
        $db->setQuery( $sqlContent );
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		
		$access = $db->loadObject();

		if ($task=="") {
			// if public then no check
			if ($access!="")
				$catid = $access->catid;
			else
				$catid = "";
			
			if ($access==null || (is_null($access->contaccess) || $access->contaccess == 0) && (is_null($access->cataccess) || $access->cataccess == 0))
				$catid = "";

			switch($view){
				case 'article':
					$task = 'access';
					break;
				default:
					$task = '';
					break;
			}
		} else {
			if ($access!="")
				$catid = $access->catid;
			else
				$catid = "";

			switch($task) {
				case 'apply':
					$task= 'save';
					break;
				case 'cancel':
					$catid = '';
					$task= 'edit';
			}
		}
	        
        $result = array(
            'task' => $task,
            'params' => array(
                '$catid' => $catid
            )
        );

		return $result;
    }
	function afteradministrator() {
		$task = JRequest::getCMD('task');

		if ($task=='edit'||$task=='add'||$task=='new') {
		    $db = & JFactory::getDBO();
			if ($task=='new') $task = 'add';
			//get id from content
			$cid			= JRequest::getVar( 'cid', array(0), '', 'array' );
			JArrayHelper::toInteger($cid, array(0));
			$id				= JRequest::getVar( 'id', $cid[0], '', 'int' );
			$catid			= JRequest::getVar( 'catid', -1, '', 'int' );
			$sectionid		= JRequest::getVar( 'sectionid', -1, '', 'int' );
	
			if ($sectionid==-1) {
				$sqlContent = "SELECT sectionid FROM #__content WHERE id = {$id}";
				$db->setQuery( $sqlContent );
				$sectionid = $db->loadResult();
				if ($sectionid=="") $sectionid=-1;
			}
	
			if ($catid==-1) {
				$sqlContent = "SELECT catid FROM #__content WHERE id = {$id}";
				$db->setQuery( $sqlContent );
				$catid = $db->loadResult();
				if ($catid=="") $catid=-1;
			}
			$user = Jfactory::getUSER();
	
			//geting usertype from user
			$arrMultiGroups[] = $user->usertype;
	
			//get multigrop names if user have it
			$sqlGetMultigroups = "SELECT grp.name FROM #__core_acl_aro_groups as grp, #__noixacl_multigroups multigrp WHERE grp.id = multigrp.id_group AND multigrp.id_user = {$user->id}";
			$db->setQuery( $sqlGetMultigroups );
			$multiGroups = $db->loadObjectList();
	
			if( !empty($multiGroups) ){
				foreach($multiGroups as $mgrp){
					$arrMultiGroups[] = $mgrp->name;
				}
			}
	
			//geting categories from multigroups
			$acl_sql = "SELECT DISTINCT axo_section FROM #__noixacl_rules WHERE aro_section = 'users' AND aro_value IN ('". implode("','",$arrMultiGroups) ."') AND axo_value ='".$task."'";
			$db->setQuery( $acl_sql );
			$cats = $db->loadObjectList();
			$catids = array();	
			if( !empty($cats) ){
				foreach($cats as $cat){
					$catids[] = $cat->axo_section;
				}
			}
			$cat_list = implode('\', \'', $catids);

			$javascript = "onchange=\"changeDynaList( 'catid', sectioncategories, document.adminForm.sectionid.options[document.adminForm.sectionid.selectedIndex].value, 0, 0);\"";
	
			$query = 'SELECT DISTINCT s.id, s.title' .
					' FROM #__sections AS s' .
					' , #__categories AS c' .
					' WHERE s.id = c.section' .
					' AND c.id IN ( \''.$cat_list.'\' )' .
					' ORDER BY s.ordering';
			$db->setQuery($query);
	
			$sections[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Section').' -', 'id', 'title');
			$sections[] = JHTML::_('select.option', '0', JText::_('Uncategorized'), 'id', 'title');
			$sections = array_merge($sections, $db->loadObjectList());
			$lists['sectionid'] = JHTML::_('select.genericlist',  $sections, 'sectionid', 'class="inputbox" size="1" '.$javascript, 'id', 'title', intval($sectionid));
	
			foreach ($sections as $section)
			{
				$section_list[] = (int) $section->id;
				// get the type name - which is a special category
				if ($sectionid) {
					if ($section->id == $sectionid) {
						$contentSection = $section->title;
					}
				} else {
					if ($section->id == $sectionid) {
						$contentSection = $section->title;
					}
				}
			}
	
			$sectioncategories = array ();
			$sectioncategories[-1] = array ();
			$sectioncategories[-1][] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
			$section_list = implode('\', \'', $section_list);
	
			$query = 'SELECT id, title, section' .
					' FROM #__categories' .
					' WHERE section IN ( \''.$section_list.'\' )' .
					' AND id IN ( \''.$cat_list.'\' )' .
					' ORDER BY ordering';
			$db->setQuery($query);
			$cat_list = $db->loadObjectList();

			// Uncategorized category mapped to uncategorized section
			$uncat = new stdClass();
			$uncat->id = 0;
			$uncat->title = JText::_('Uncategorized');
			$uncat->section = 0;
			$cat_list[] = $uncat;
			foreach ($sections as $section)
			{
				$sectioncategories[$section->id] = array ();
				$rows2 = array ();
				foreach ($cat_list as $cat)
				{
					if ($cat->section == $section->id) {
						$rows2[] = $cat;
					}
				}
				foreach ($rows2 as $row2) {
					$sectioncategories[$section->id][] = JHTML::_('select.option', $row2->id, $row2->title, 'id', 'title');
				}
			}
			$sectioncategories['-1'][] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
			$categories = array();
			foreach ($cat_list as $cat) {
				if($cat->section == $sectionid)
					$categories[] = $cat;
			}
	
			$categories[] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
			$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1"', 'id', 'title', intval($catid));

			$i = 0;
			$catjavacode = "var sectioncategories = new Array;\n\t\t";
			foreach ($sectioncategories as $k=>$items) {
				foreach ($items as $v) {
					$catjavacode .=  "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
				}
			}

			// Replace 
			$document    = &JFactory::getDocument(); 
			$buf = $document->getBuffer('component');

			$start = strpos($buf, '<select name="catid" id="catid"');
			if ($start!=0) {
				$end = strpos($buf, '</option></select>', $start);
				$buf = substr($buf,0, $start-1).$lists['catid'].substr($buf,$end);
			}

			$start = strpos($buf, '<select name="sectionid" id="sectionid"');
			if ($start!=0) {
				$end = strpos($buf, '</option></select>', $start);
				$buf = substr($buf,0, $start-1).$lists['sectionid'].substr($buf,$end);
			}

			$start = strpos($buf, 'var sectioncategories = new Array;');
			if ($start!=0) {
				$end = strpos($buf, 'function submitbutton(pressbutton)', $start);
				$buf = substr($buf,0, $start-1).$catjavacode.substr($buf,$end);
			}

			$document->setBuffer($buf, 'component');
		}
	}
	function aftersite() {
		$this->afteradministrator();
	}

}