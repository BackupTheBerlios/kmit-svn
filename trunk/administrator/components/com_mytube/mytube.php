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

/*
 * Make sure the user is authorized to view this page
 */
$user = & JFactory::getUser();

if (!$user->authorize( 'com_users', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

$document	= & JFactory::getDocument();
$document->addStyleSheet('components/com_mytube/assets/css/styles.css');
$document->addScript("components/com_mytube/assets/js/mytube.js");

// Include the mytube Library
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mytube'.DS.'includes'.DS.'mytube_remotevideo.php');

// Include the mytube configs file
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mytube'.DS.'configs'.DS.'configs.mytube.php');

//Require helperfile
require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helpers.php');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if( $controller = JRequest::getWord('controller') ) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

//Create the controller
$classname  = 'mytubeController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getWord('task'));
$controller->redirect();

