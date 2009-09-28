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

class mytubeViewTemplates extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        //Load pane behavior
        jimport('joomla.html.pane');
        $pane   	= & JPane::getInstance('tabs');

        //initialise variables
        $editor 	= & JFactory::getEditor();
        $document	= & JFactory::getDocument();
        $user 		= & JFactory::getUser();

        //get vars
        $cid 		= JRequest::getVar( 'cid' );

        //create the toolbar
        if ( $cid ) {
            JToolBarHelper::title( JText::_( 'EDIT VIDEO' ), 'mytube-videos' );
        } else {
            JToolBarHelper::title( JText::_( 'ADD VIDEO' ), 'mytube-videos' );
        }
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();

        $tmplpath = dirname(__FILE__).DS.'tmpl';
        $this->assignRef('tmplpath'			, $tmplpath);

        $this->assignRef('my', $my =& JFactory::getUser());
        $this->assignRef('pane'			, $pane);
        parent::display($tpl);
    }
}
?>