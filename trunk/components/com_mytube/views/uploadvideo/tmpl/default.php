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

<div id="videomainbody">
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

<!--############ LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('mytube_upload_left', $this->c->upload_column_left_width, 'left')?>

<!--############ MIDDLE COLUMN ###########-->
    <div id="videomiddle-column" style="width:<?php echo $this->c->upload_column_center_width?>px">
			<div class="uploadvideo-title">Upload Video To Server For Conversion</div>
				<table cellspacing="0" cellpadding=4" border="0">
				    <tr><td>This function only for purcharse version</td></tr>
				</table>
			</div>

	    <!--############## CUSTOM DISCLAIMER MODULE POSITION ###########-->
	    <div>
	          <?php
	          $mytube_upload_disclaimer_modules = &JModuleHelper::getModules('upload_disclaimer');
	          foreach ($mytube_upload_disclaimer_modules as $upload_disclaimer_module) {
	              echo "<div id=\"disclaimer-module\">";
	              echo JModuleHelper::renderModule($upload_disclaimer_module);
	              echo "</div>";
	          }
	            ?>
	    </div>
	    <!--############## CUSTOM DISCLAIMER MODULE POSITION END ###########-->

	</div>
<!--############ RIGHT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('mytube_upload_right', $this->c->upload_column_right_width, 'right')?>

    <?php echo JHTML::_( 'form.token' ); ?>
    <input type="hidden" name="date_updated" value="<?php echo date("Y-m-d") ?>"/>
    <input type="hidden" name="date_added" value="<?php echo date("Y-m-d") ?>"/>
    <input type="hidden" name="video_type" value="local"/>
    <input type="hidden" name="option" value="com_mytube" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" id="task" name="task" value="uploadvideo" />
    </form>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
