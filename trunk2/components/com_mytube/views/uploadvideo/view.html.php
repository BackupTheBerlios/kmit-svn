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

class mytubeViewUploadVideo extends JView {

    function display($tpl = null)
    {
        global $mainframe;
        $c = mytube_configs::get_instance();

        //check user login
        $user 		= & JFactory::getUser();
        mytube::checkPermission("acl_uploadvideo");

        //$this->setModel('video');
        $model = $this->getModel('video');
        $categories =  $model->getParentOption();

        $parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'class="inputbox" size="1" ', 'value', 'text', '');

        $this->assignRef('c', $c);

        $this->assignRef('parentSelect', $parentSelect);
        parent::display($tpl);
    }
}
?>