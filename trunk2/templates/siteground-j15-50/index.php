<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
JPlugin::loadLanguage( 'tpl_SG1' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

</head>
<body id="page_bg">
		<div id="top"></div>
		<div id="header">
			<div id="lpart">
				<div id="pathway">
					<jdoc:include type="module" name="breadcrumbs" />
				</div>
				
				<div id="logo">
					<a href="index.php"><?php echo $mainframe->getCfg('sitename') ;?></a>
				</div>
			</div>
			<div id="newsflash">
				<jdoc:include type="modules" name="top" />
			</div>
			<div class="clr"></div>
		</div>
		<div class="pill_m">
			<div id="pillmenu">
				<jdoc:include type="modules" name="user3" />
			</div>
		</div>	
		<div class="clr"></div>
		
	
	
	<div class="center">		
		<div id="wrapper">
			<div id="content_top"></div>
			<div id="content">
				<?php if($this->countModules('left') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="leftcolumn">	
					<jdoc:include type="modules" name="left" style="rounded" />
					
				</div>
				<?php endif; ?>
				
				<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="maincolumn">
				<?php else: ?>
				<div id="maincolumn_full">
				<?php endif; ?>
					<div class="nopad">				
						<jdoc:include type="message" />
						<?php if($this->params->get('showComponent')) : ?>
							<jdoc:include type="component" />
						<?php endif; ?>
					</div>
				</div>
				<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
				<div id="rightcolumn" style="float:right;">
					<jdoc:include type="modules" name="right" style="rounded" />								
				</div>
				<?php endif; ?>
				<div class="clr"></div>
			</div>
		</div>
		<div id="content_bottom">
			<p>
				Valid <a href="http://validator.w3.org/check/referer">XHTML</a> and <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>.
			</p>
		</div>				
	<jdoc:include type="modules" name="debug" />
	</div>
	
	<div id="footer">
		
		<div id="sgf">
			<?php $sg = ''; include "templates.php"; ?>
		</div>
	</div>	
</body>
</html>