<?php
/**
 * @ version	$Id: multishare.php 2009-04-20  v1.2.5$
 * @ package	Multishare
 * @ Copyright (C) 2009 by Juan Padial. All rights reserved.
 * @ license	GNU/GPL
 * @ Website    http://www.shikle.com/
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentmultishare extends JPlugin {

	function plgContentmultishare( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe;		

		$document	= & JFactory::getDocument();
		$view		= JRequest::getCmd('view');
		
		if ( $view != 'article' ) return;		
		
		// Get plugin info
		$plugin =& JPluginHelper::getPlugin('content', 'multishare');
		$pluginParams = new JParameter( $plugin->params );
		
		$excludeSectionID    = $pluginParams->get( 'excludeSectionID',  '' );		
		$excludeCategoryID   = $pluginParams->get( 'excludeCategoryID', '' );
		$excludeID           = $pluginParams->get( 'excludeID',         '' );		
		$listexcludeSection  = @explode ( ",", $excludeSectionID );	
		$listexcludeCategory = @explode ( ",", $excludeCategoryID );	
		$listexclude 	   = @explode ( ",", $excludeID );		
		if ( $params->get( 'intro_only' ) || in_array ( $article->id, $listexclude ) || in_array ( $article->sectionid, $listexcludeSection ) || in_array ( $article->catid, $listexcludeCategory ) ) return;
		$shareservice           = $pluginParams->def( 'shareservice','' );
		$addthis_pub   		= $pluginParams->def( 'addthis_pub', '' );
		$LogoURL          	= $pluginParams->def( 'LogoURL','');
		$Language               = $pluginParams->def( 'Language','en');
		$AddThisBrand           = $pluginParams->def( 'addthis_brand','');
		$AddThisLogoBackground  = $pluginParams->def( 'addthis_logo_background_color','');
		$AddThisLogoColor       = $pluginParams->def( 'addthis_logo_color','');
		$AddThisServicesOrder   = $pluginParams->get( 'addthis_services_order',  '' );
	        $AddThisBrandColor       = $pluginParams->def( 'addthis_brand_color','');
		$AddThisBrandBackgroundColor   = $pluginParams->get( 'addthis_brand_background_color',  '' );
		$URI               = $pluginParams->get( 'URI',  '' );
		$Buttonimage   = $pluginParams->get('Buttonimage',  'http://s7.addthis.com/static/btn/lg-share-' . $Language . '.gif');
		$SharethisCode    = $pluginParams->get( 'SharethisCode',  '' );
		$TellafriendCode    = $pluginParams->get( 'TellafriendCode',  '' );
		$CustomCode    = $pluginParams->get( 'CustomCode',  '' );
		$show          = $pluginParams->get( 'Show',  '' );
		$css           = $pluginParams->get( 'DivCSS',  '' );
		$html  = "";
		$code ="";
		if ( $shareservice == '1' ){
		$code  ="<div style=\"$css\">";
		$code .="<!-- ADDTHIS BUTTON BEGIN -->
<script type=\"text/javascript\">
addthis_pub             = '$addthis_pub'; 
addthis_language        = '$Language';
addthis_logo            = '$LogoURL';
addthis_logo_background = '$AddThisLogoBackground';
addthis_logo_color      = '$AddThisLogoColor';
addthis_brand           = '$AddThisBrand';
addthis_options         = '$AddThisServicesOrder';
addthis_header_color = '#$AddThisBrandColor';
addthis_header_background = '#$AddThisBrandBackgroundColor';
</script>
<a href=\"$URI\" onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]')\" onmouseout=\"addthis_close()\" onclick=\"return addthis_sendto()\"><img src=\"$Buttonimage\" alt=\"Addthis\" border=\"0\"/></span></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/200/addthis_widget.js\"></script>
<!-- ADDTHIS BUTTON END -->\n";	
              $code .="</div>";
	 }
	 if ( $shareservice == '2' ){
              $code  ="<div style=\"$css\">";
	      $code .="$SharethisCode\n";
	      $code .="</div>";
	 }
	 if ( $shareservice == '3' ){
              $code  ="<div style=\"$css\">";
	      $code .="$TellafriendCode\n";
	      $code .="</div>";
	 }
	 if ( $shareservice == '4' ){
	      $code  ="<div style=\"$css\">";
	      $code .="$CustomCode\n";
	      $code .="</div>";
	 }
       if ($show == '1'){
            $html = $article->text;
            $html .= $code;
            $article->text = $html;
        }
        if ($show == '2'){
            $html = $code;
            $html .= $article->text;
            $article->text = $html;
        }
        if ($show == '3'){
            $html  = $code;
            $html .= $article->text;
            $html .= $code;
            $article->text = $html;
        }
    }
}
?>