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
<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Comments Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Comments Integration');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Commenting Component');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->commenting_integration, 'commenting_system', 'disabled=\'true\'', 'value', 'text', $this->c->commenting_system); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--JomSocial Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Community Integration');?></legend>
    <table class="admintable" cellspacing="1">
        <col width=""></col>
        <col width="400"></col>
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('JomSocial Component');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->community, 'community', 'disabled=\'true\'', 'value', 'text', $this->c->community); ?>
	       </td>
	       <td>

	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>