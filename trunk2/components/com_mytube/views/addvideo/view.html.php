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
 * View class for the MyTube Video screen
 *
 * @package Joomla
 * @subpackage MyTube
 * @since 0.9
 */
class mytubeViewAddvideo extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        //check permission
        mytube::checkPermission("acl_addvideo");

        $c = mytube_configs::get_instance();
        $this->assignRef('c'      	, $c);

        parent::display($tpl);
    }
}
?>