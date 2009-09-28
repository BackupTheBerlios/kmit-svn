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

class mytubeViewVideo extends JView {

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

        $c = mytube_configs::get_instance();
        $this->assignRef('c'      	, $c);

        //get vars
        $cid 		= JRequest::getVar( 'cid' );

        //create the toolbar
        if ( $cid ) {
            JToolBarHelper::title( JText::_( 'EDIT VIDEO' ), 'mytube-videos' );

        } else {
            JToolBarHelper::title( JText::_( 'ADD VIDEO' ), 'mytube-videos' );

        }

        JToolBarHelper::spacer();

        //Get data from the model
        $model		= & $this->getModel();
        $row     	= & $this->get( 'Data' );

        if ($row->id != 0 && $row->video_type != 'local' && $row->video_type != null) {
            $r = new JApplication();
            $r->redirect('index.php?option=com_mytube&controller=videos&task=applylink&cid=' . $row->id);

            $ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', $row->published == '' ? 1 : $row->published);
            $isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', $row->downloadable == '' ? 1 : $row->downloadable );
            $isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', $row->featured);
        }

        $this->assignRef('my', $my =& JFactory::getUser());
        parent::display($tpl);
    }
}
?>