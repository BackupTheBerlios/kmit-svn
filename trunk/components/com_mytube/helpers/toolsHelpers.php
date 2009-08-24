<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined('_JEXEC') or die('Restricted access');
class toolsHelpers {
    function integrateVideoComments($row) {
        $c = mytube_configs::get_instance();
        $mytubeCommentingSystemCode = '';
        if ($c->commenting_system != 'No') {
            //integrate commenting system with JomComment
            if ($c->commenting_system == 'JomComment') {
                if (file_exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php')) {
                    require_once(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
                    $mytubeCommentingSystemCode = jomcomment( $row->id, 'com_mytube');
                } else {
                    $mytubeCommentingSystemCode = '<b>You must install JomComment component to use comment function</b>';
                }
            }
            //integrate commenting system with JComment
            if ($c->commenting_system == 'JComment') {
                if (file_exists(JPATH_SITE.DS.'components/com_jcomments/jcomments.php')) {
                    require_once(JPATH_SITE.DS.'components/com_jcomments/jcomments.php');
                    $mytubeCommentingSystemCode = JComments::showComments( $row->id, 'com_mytube', $row->video_title );
                } else {
                    $mytubeCommentingSystemCode = '<b>You must install JComment component to use comment function</b>';
                }
            }
        }
        return $mytubeCommentingSystemCode;
    }
}
?>