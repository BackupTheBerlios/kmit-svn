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
 * View class for the mytube Video screen
 *
 * @package Joomla
 * @subpackage EventList
 * @since 0.9
 */
class mytubeViewAddvideo extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$pane 		= & JPane::getInstance('sliders');

		JToolBarHelper::spacer();
		JToolBarHelper::save('applylink');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
    	JToolBarHelper::title( JText::_( 'ADD REMOTE VIDEO' ), 'mytube-videos' );
		parent::display($tpl);
	}
}
?>