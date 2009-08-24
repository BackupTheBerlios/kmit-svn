<?php
/*
# "VOTItaly" Plugin for Joomla! 1.5.x - Version 1.1
# License: http://www.gnu.org/copyleft/gpl.html
# Authors: Luca Scarpa & Silvio Zennaro
# Copyright (c) 2006 - 2009 Siloos snc - http://www.siloos.it
# Project page at http://www.joomitaly.com - Demos at http://demo.joomitaly.com
# ***Last update: Jan 06th, 2009***
*/

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define('JPATH_CORE', JPATH_BASE . '/../..');
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$cfg =& JFactory::getConfig();
$db  =& JFactory::getDBO();

$rate = (int) JRequest::getVar( 'rating', false );
$cid  = (int) JRequest::getVar( 'cid', false );

$status_code = storeVote($cid, $rate); 

function storeVote($content_id, $user_rating) {
	global $db;
	
	$query = 'SELECT *' .
			' FROM #__content_rating' .
			' WHERE content_id = '.(int) $content_id;
	$db->setQuery($query);
	$rating = $db->loadObject();
	
	if (!$rating)	{
		$prev_rating_count = 0;
		$prev_rating_sum = 0;
	} else {
		$prev_rating_count = $rating->rating_count;
		$prev_rating_sum = $rating->rating_sum;		
	}

	$error = 0;
	$message = '';
	if ( $user_rating >= 1 && $user_rating <= 5) {
		$userIP =  $_SERVER['REMOTE_ADDR'];

		if (!$rating) {
			// There are no ratings yet, so lets insert our rating
			$query = 'INSERT INTO #__content_rating ( content_id, lastip, rating_sum, rating_count )' .
					' VALUES ( '.(int) $content_id.', '.$db->Quote($userIP).', '.(int) $user_rating.', 1 )';
			$db->setQuery($query);
			if (!$db->query()) {
				$error = 1;
				$message = $db->stderr();
			} else {
				$rating_count = 1;
				$rating_sum = $user_rating;
			}
		} else {
			if ($userIP != ($rating->lastip)) {
				// We weren't the last voter so lets add our vote to the ratings totals for the article
				$query = 'UPDATE #__content_rating' .
						' SET rating_count = rating_count + 1, rating_sum = rating_sum + '.(int) $user_rating.', lastip = '.$db->Quote($userIP) .
						' WHERE content_id = '.(int) $content_id;
				$db->setQuery($query);
				if (!$db->query()) {
					$error = 1;
					$message = $db->stderr();
				} else {
					$rating_count = $prev_rating_count + 1;
					$rating_sum = $prev_rating_sum + $user_rating;
				}
			} else {
				$error = 2; // già votato!
			}
		}
	} else $error = 3;
	
	if (!$error) {
		$average = number_format(intval($rating_sum) / intval( $rating_count ),2);
		$width   = $average * 20;
	} else {
		$average = ($prev_rating_count ? number_format(intval($prev_rating_sum) / intval( $prev_rating_count ),2) : 0 );
		$width   = $average * 20;
	}
?>
{
	success: <?php echo ( $error ? 'false' : 'true' ); ?>, 
	return_code: <?php echo $error; ?>,
	message: "<?php echo $message; ?>",
	width: <?php echo ( false ? '""' : '"'.$width.'%"' ); ?>,
	num_votes: <?php echo ( $error ? $prev_rating_count : $rating_count ); ?>, 
	average: <?php echo ( false ? '""' : $average ); ?>, 
	out_of: 5
}
<?php
}