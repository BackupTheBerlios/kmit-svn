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
<?php $mosConfig_live_site = substr(JURI::base(), 0, strlen(JURI::base()) -15);?>
<fieldset class="adminform">
<table class="noshow" border="0">
    <col width="25%"></col>
    <tr>
        <td align="left">
            <fieldset class="adminform">
                <legend><?php echo JText::_('Bekle Skin');?> <input type="radio" name="jw_player_skin" value="bekle" <?php echo $this->c->jw_player_skin == "bekle" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_mytube/assets/swf/player.swf"bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_mytube/assets/swf/skins/bekle.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>
            </fieldset>
        </td>
    </tr>
</table>
</fieldset>