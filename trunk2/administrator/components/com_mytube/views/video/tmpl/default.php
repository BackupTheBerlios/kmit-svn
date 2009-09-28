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

<script language="javascript" type="text/javascript">
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

function selectCategory() {
    document.getElementById('task').value = 'selelctCat';
    document.adminForm.submit();
}

function selectthisvideo() {
    var file=document.getElementById("video_url").value;
    file = file.replace(/_/g, ' ');
    file = file.substr(0, file.length - 4);
    file = capitalizeMe(file);
    document.getElementById('video_title').value = file;
    document.getElementById('video_desc').value = file;
}

function capitalizeMe(str) {
    newStr = '';
    str = str.split(' ');
    for(var c=0; c < str.length; c++) {
        newStr += str[c].substring(0,1).toUpperCase() +
        str[c].substring(1,str[c].length) + ' ';
    }
    return newStr;
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm"  enctype="multipart/form-data">
	<table  cellspacing="0" cellpadding="4" border="0">
		<tr>
			<td><h2>Adding Local Video Settings</h2></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">This function only for purchase version</td>
		</tr>
	</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_mytube" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" name="view" value="video" />
<input type="hidden" id="task" name="task" value="" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
