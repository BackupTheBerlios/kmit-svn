<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined('_JEXEC') or die('Restricted access');

/**
 * EventList categories Model class
 *
 * @package Joomla
 * @subpackage jomTube
 * @since 0.9
 */
class mytube_videos extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var int */
	var $user_id			= 0;
	/** @var string */
	var $video_title 			= '';
	/** @var string */
	var $video_desc 	= null;
	/** @var string */
	var $category_id 	= null;
	var $video_type = null;
	var $video_url = null;
	var $video_thumb = null;
	var $downloadable = null;
	var $featured = null;
	var $published = null;
	var $duration = null;
	var $tags = null;
	var $date_added = null;
	var $hits = null;
	var $votetotal = null;
	var $remote_id = null;
	var $date_updated = null;
	var $display_thumb = null;

	/**
	* @param database A database connector object
	*/
	function mytube_videos(& $db) {
		parent::__construct('#__jomtube_videos', 'id', $db);
	}

	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->video_title) == '') {
			$this->_error = JText::_( 'ADD TITLE VIDEO' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		return true;
	}
}
?>