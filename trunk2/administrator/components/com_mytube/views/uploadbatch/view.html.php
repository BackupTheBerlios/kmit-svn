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

class mytubeViewUploadbatch extends JView {

	function display($tpl = null)
	{
		global $mainframe;

        JToolBarHelper::title( 'Batch Adding Videos Locally To Video List' , 'mytube-videos' );

		JToolBarHelper::spacer();

    	$this->assignRef('my', $my =& JFactory::getUser());

		parent::display($tpl);
	}
}
?>