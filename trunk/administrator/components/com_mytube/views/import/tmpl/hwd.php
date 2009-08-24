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
<!--Import Videos from Seyret-->
<form action="index.php?option=com_mytube" method="post" id="adminForm" name="adminForm">
<fieldset class="adminform">
    <legend><?php echo JText::_('Import Videos from HWDVideoShare');?> <span class="mytube_warning">(Only support remote video)</span></legend>
    <table class="admintable" cellspacing="1">
        <col width="250"></col>
	<tbody>
	   <?php if ($this->hwd_check):?>
                <tr>
                    <td class="key">Import from HWDVideoShare category</td>
                    <td><?php echo $this->hwdcatsel; ?></td>
                </tr>
                <tr>
                    <td class="key">Import into MyTube category</td>
                    <td><?php echo $this->parentSelect; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Import Now" /></td>
                </tr>
            <?php else: ?>
                <span class="mytube_warning"><?php echo JText::_("HWDVideoShared component is not yet installed on this Joomla website");?></span>
            <?php endif;?>
	</tbody>
    </table>
</fieldset>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_mytube" />
<input type="hidden" name="controller" value="import" />
<input type="hidden" name="view" value="import" />
<input type="hidden" name="task" value="hwdImport" />
</form>