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

<!--LANGUAGE SETTING-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Mytube Language Manager');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="15"></col>
	<tbody>
	   <tr>
	       	<td class="key"><?php echo JText::_('Currently Selected Language');?></td>
	       	<td><?php echo JHTML::_('select.genericlist', $this->jtube_language, 'jtube_language', 'disabled=\'disabled\'', 'value', 'text', $this->c->jtube_language); ?></td>
			<td></td>
			<td><span class="mytube_message">(Displays Only In The Frontend)</span></td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--THEME SETTING-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Mytube Theme Mananger');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Currently Selected Theme');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->jtube_skin, 'jtube_skin', 'disabled=\'disabled\'', 'value', 'text', $this->c->jtube_skin); ?>
	           <input type="hidden" name="jtube_skin" value="solar_sentinel_lightgray" />
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--CHECK PERMISSIONS-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Directory Permission Check');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('The configruation file');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $configfile = 'components/com_mytube/configs/configs.mytube.php';
                        $permission = is_writeable($configfile);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('The mytubefile directory');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $jomtubefiles_directory = JPATH_SITE.DS.'jomtubefiles';
                        $permission = is_writeable($jomtubefiles_directory);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>