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

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Users
 * @since 1.5
 */
class mytubeController extends JController
{
    /**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */

    function __construct($config = array())
    {
        parent::__construct($config);

        /* Setup our toolbar buttons */
        JToolBarHelper::title( JText::_( 'mytube! Settings' ), 'mytube-configuration' );

        $taskName = JRequest::getVar('task', '');
        $viewName = JRequest::getVar('view', '');

        //JSubMenuHelper::addEntry(JText::_('Configuration'), 'index.php?option=com_mytube&task=config', $taskName == "config");
        JSubMenuHelper::addEntry(JText::_('Configuration'), 'index.php?option=com_mytube&view=configs', $viewName == "configs");
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_mytube&view=categories', $viewName == "categories");
        JSubMenuHelper::addEntry(JText::_('Videos'), 'index.php?option=com_mytube&view=videos', $viewName == "videos");

        /*
        * Added 2009-04-06
        * Version 1.0.5
        */
        JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_mytube&view=plugins', $viewName == "plugins");
        /*End 2009-04-06*/

        parent::__construct();

    }

    /**
	 * Displays a view
	 */
    function display( )
    {
        global $mainframe;
        $document	= & JFactory::getDocument();
        //$params = clone($mainframe->getParams('com_mytube'));
        $viewName = JRequest::getCmd( 'view', 'videos' );
        $viewType = $document->getType();
        $viewLayout = JRequest::getCmd( 'layout', 'default' );

        $view = &$this->getView($viewName, $viewType);
        $model_category = &$this->getModel('category');
        // Configure the view.
        $view->setModel($model_category, false);

        switch($this->getTask())
        {
            case "do_videoconfig":
                $model = &$this->getModel( 'video_display_settings' );
                $model->do_video_display_settings();
                $this->setup_video_display_variables();
                JToolBarHelper::save( 'do_videoconfig' );
                JRequest::setVar('view', 'video_display_settings');
                JRequest::setVar('saved', '1');
                break;
            case "":
                if (JRequest::getVar('view') == '')
                    JRequest::setVar('view', 'videos');
                break;
            case "config":
                $model = &$this->getModel( 'video_display_settings' );
                $this->setup_video_display_variables();
                JToolBarHelper::save( 'do_videoconfig' );
                JRequest::setVar('view', 'video_display_settings');
                break;
        }


        JToolBarHelper::back( 'back' );

        parent::display();
    }

}
