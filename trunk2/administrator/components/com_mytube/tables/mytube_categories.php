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
 * @subpackage MyTube
 * @since 0.9
 */
class mytube_categories extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var int */
	var $parent_id			= 0;
	/** @var string */
	var $category_name 			= '';
	/** @var string */
	var $category_info 	= null;
	/** @var string */
	var $family_id 	= null;
	var $directory = null;
	var $thumbnail = null;
	/**
	* @param database A database connector object
	*/
	function mytube_categories(& $db) {
		parent::__construct('#__jomtube_categories', 'id', $db);
	}

	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->category_name ) == '') {
			$this->_error = JText::_( 'ADD NAME CATEGORY' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}

		return true;
	}
}
?>