<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined('_JEXEC') or die('Restricted access');
?>

<script>
function submitbutton(pressbutton)
{
    var form = document.adminForm;

    if (!checkvideo()) {
        return false;
    }

    if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
    }

    submitform( pressbutton );
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">


<table cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td><h2>Video Batch Adding Settings</h2></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2">This function only for purchase version</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_mytube" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" name="view" value="video" />
<input type="hidden" id="task" name="task" value="" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
