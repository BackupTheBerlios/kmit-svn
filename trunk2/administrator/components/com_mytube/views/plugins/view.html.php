<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the mytube categories screen
 *
 * @package Joomla
 * @subpackage mytube
 * @since 0.9
 */

class mytubeViewPlugins extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();

		//Load pane behavior
        jimport('joomla.html.pane');
        $pane   	= & JPane::getInstance('tabs');
        $this->assignRef('pane'			, $pane);

		JHTML::_('behavior.tooltip');

		//get vars

		//create the toolbar
		JToolBarHelper::title( JText::_( 'Plugins' ), 'mytube-plugins' );
		JToolBarHelper::spacer();

		//Get data from the model
		$model = & $this->getModel();
		$rows      	= $model->getData('thirdparty');
		$templates  = $model->getData('template');
		$languages  = $model->getData('language');

		$total      = & $this->get( 'Total');
		$pageNav 	= & $this->get( 'Pagination' );

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('languages'      	, $languages);
		$this->assignRef('templates'      	, $templates);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('user'			, $user);

		$tmplpath = dirname(__FILE__).DS.'tmpl';
        $this->assignRef('tmplpath'			, $tmplpath);

		parent::display($tpl);
	}
}
?>