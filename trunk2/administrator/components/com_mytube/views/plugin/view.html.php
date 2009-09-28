<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Plugins component
 *
 * @static
 * @package		Joomla
 * @subpackage	MyTube
 * @since 1.5
 */
class MyTubeViewPlugin extends JView
{
	function display( $tpl = null )
	{
		global $option;

		$db		=& JFactory::getDBO();
		$user 	=& JFactory::getUser();

		$client = JRequest::getWord( 'client', 'site' );
		$id 	= JRequest::getVar( 'id', '', '', 'int' );

		$lists 	= array();

		$query = 'SELECT * FROM #__jomtube_plugins WHERE id = ' . $id;
		$db->setQuery($query);
		$row 	= $db->loadObject();
		if ($id)
		{
			$data = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . DS .$row->folder . DS . $row->element .'.xml');
		} else {
			$row->folder 		= '';
			$row->ordering 		= 999;
			$row->published 	= 1;
			$row->description 	= '';
		}

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $row->published );

		// get params definitions
		$params = new JParameter( $row->params, JPATH_SITE . DS .$row->folder . DS . $row->element .'.xml', 'plugin' );

		$this->assignRef('lists',		$lists);
		$this->assignRef('plugin',		$row);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}
}