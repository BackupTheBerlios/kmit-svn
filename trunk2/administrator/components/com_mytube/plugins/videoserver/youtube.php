<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

function youtubegetvideodetails($vidlink, $existingcode, $categorylist, $reqtype){
    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

    if ($reqtype=="new") {
		// EXAMPLE LINK - http://www.youtube.com/watch?v=GKeBTmXiYEc
		$find_video_id = preg_match('/http:\/\/(?:\w+\.)?youtube\.com\/watch\?.*v=([^&]+).*$/',$vidlink,$video_ID);
		$smallvideocode = $video_ID[1];
    } else if ($reqtype=="refresh") {
       	$smallvideocode=$existingcode;
    }

    $videoservertype="youtube";
	$remote_id = $smallvideocode;
	include_once(dirname(__FILE__).'/youtube_/scrape.php');

//############# GET VIDEO INFO http://www.youtube.com/get_video_info?video_id=u5fd5EleYKw
    $html = file_get_html("http://gdata.youtube.com/feeds/api/videos/".$smallvideocode."");

//############# GET VIDEO TITLE
	$video_title = $html->find("media:title", 0);
	$videotitle = ucwords (strtolower($video_title->innertext)); // video_title-> FIELD name in Database
	$videotitle = htmlentities($videotitle, ENT_NOQUOTES);

//############# GET VIDEO DECSCRITPITION
	$video_desc = $html->find("media:description", 0);
	$video_desc = $video_desc->innertext;
	$strlength = strlen(".$videodescription.");
	if ($strlength == 0) {
		$videodescription = $videotitle;
	}
	$videodescription = $video_desc; // video_desc-> FIELD name in Database

//############# GET VIDEO TAGS KEYWORDS
	$tags= $html->find("media:keywords", 0);
    $tags = $tags->innertext;
	$videotags = $tags;// tags-> FIELD name in Database

//############# GET DATE VIDEO WAS PUBLISHED
	$date_added = $html->find("published", 0);
	$date_added = $date_added->innertext;
	$date_p =(date_parse($date_added));
	$video_published= date("Y-m-d",mktime(0,0,0,$date_p[month],$date_p[day],$date_p[year]));

//############# GET DATE VIDEO WAS UPDATED
	$date_updated = $html->find("updated", 0);
	$date_updated = $date_updated->innertext;
	$date_u =(date_parse($date_updated));
	$date_updated = date("Y-m-d",mktime(0,0,0,$date_u[month],$date_u[day],$date_u[year]));

//############# GET VIDEO DURATION
	$duration = $html->find("yt:duration", 0)->seconds;
    $videoduration = sec2hms($duration);

//############# GET VIDEO URL DIRECT LINK
	$video_url = $html->find("media:player", 0)->url;

//############# GET THUMNAIL URL
	$thumbnail_link = $html->find("media:thumbnail", 1)->url;

//############# GET DISPLAY THUMNAIL URL
	$display_thumb = $html->find("media:thumbnail", 3)->url;

    if ($reqtype=="new") {
        $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
        return $renderinputform;
    } else if ($reqtype=="refresh") {
        return array ($picturelink, $videotitle, $itemcomment,$videotags,$videodescription,$videoduration,$video_published,$thumbnail_link);
    }

}

function youtubeembed($video_detail, $jomtube_configs){
    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);
    $video_ID = urldecode($video_detail->remote_id);
    $display_thumb = ($video_detail->display_thumb);

// ##################################################
// ########### GET PARAMS
// ##################################################
$plugin = JTubePluginHelper::getPlugin('thirdparty', 'youtube');
// Get plugins parameters
	$params = new JParameter( $plugin->params );
// ########### YOUTUBE PARAMETERS
	$vidplayer = $params->get('vidplayer');
	$ytfs = $params->get('ytfs');
	$ythd = $params->get('$ythd');
	$ytautoplay = $params->get('ytautoplay');
	$ytref = $params->get('ytref');
	$ytshowinfo = $params->get('ytshowinfo');
	$ytshowsearch = $params->get('ytshowsearch');
	$ytshowborder = $params->get('ytshowborder');
	$ytcolor1 = $params->get('ytcolor1');
	$ytcolor2 = $params->get('ytcolor2');

// ########### JWPLAYER PARAMETERS
	$backcolor = $params->get('backcolor');
	$frontcolor = $params->get('frontcolor');
	$lightcolor = $params->get('lightcolor');
	$screencolor = $params->get('screencolor');
	$controlbar = $params->get('controlbar');
// JWPLAYER BEHAIVOR PARAMETERS
	$stretching = $params->get('stretching');
	$jwautostart = $params->get('jwautostart');
	$volume = $params->get('volume');
	$mute = $params->get('mute');
	$displayclick = $params->get('displayclick');
	$jwlink = $params->get('jwlink');
	$linktarget = $params->get('linktarget');
	$bufferlength = $params->get('bufferlength');
	$jwlogo = $params->get('jwlogo');

// ###########################################################
// ########### USE VIDEO WIDTH, HEIGHT CONFIG FROM BACKEND
// ###########################################################
    if ($jomtube_configs->video_player_height>0 AND $jomtube_configs->video_player_width>0){
        $videowidth = $jomtube_configs->video_player_width;
        $videoheight = $jomtube_configs->video_player_height;
    }

// ########### USE JWPLAYER TO VIEW VIDEOS #################
    if ($vidplayer=="jwplayer" || $vidplayer=="jwplayeryt"){
		if ($vidplayer=="jwplayeryt") $videofile = "http://www.youtube.com/watch?v=".$video_ID;
		if ($vidplayer=="jwplayer") $videofile = $mosConfig_live_site."/administrator/components/com_mytube/plugins/videoserver/youtube_/YouTube_Multi-Format.php?v=".$video_ID;

		if ($jwautostart=="true2") $jwautostart = "true"; else $jwautostart = "false";
		if ($mute=="true2") $mute = "true"; else $mute = "false";
?>

    <script type="text/javascript" language="javascript">

      var flashvars =
      {
        'file':                                  encodeURIComponent('<?php echo $videofile;?>'),
        'type':                                  'video',
		'skin':									 '<?php echo $mosConfig_live_site;?>/components/com_mytube/assets/swf/skins/<?php echo $jomtube_configs->jw_player_skin ?>.swf',
		'controlbar':							 '<?php echo $controlbar;?>',
		'logo':									 '<?php echo $jwlogo;?>',
        'stretching':                            '<?php echo $stretching;?>',
        'frontcolor':                            '<?php echo $frontcolor;?>',
        'backcolor':                             '<?php echo $backcolor;?>',
        'screencolor':                           '<?php echo $screencolor;?>',
        'lightcolor':                            '<?php echo $lightcolor;?>',
        'autostart':                             '<?php echo $jwautostart;?>',
        'volume':                           	 '<?php echo $volume;?>',
        'mute':  	                          	 '<?php echo $mute;?>',
        'displayclick':                          '<?php echo $displayclick;?>',
        'link': 		                         '<?php echo $jwlink;?>',
        'linktarget': 		                     '<?php echo $linktarget;?>',
        'bufferlength':                          '<?php echo $bufferlength;?>'
      };

      var params =
      {
        'allowfullscreen':                       'true',
        'allowscriptaccess':                     'always'
      };

      var attributes =
      {
        'name':                                  'JomPlayer',
        'id':                                    'JomPlayer'
      };

      swfobject.embedSWF('<?php echo $mosConfig_live_site;?>/components/com_mytube/assets/swf/player.swf', 'JomTubePlayer', '<?php echo $videowidth;?>', '<?php echo $videoheight;?>', '9.0.124', false, flashvars, params, attributes);
    </script>

<?php

	$jwplayer_embed = "<div id=\"JomPlayerContainer\" class=\"JomPlayerContainer\">
	      				<a id=\"JomTubePlayer\" class=\"player1\" href=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\">Get the Adobe Flash Player to see this video.</a>
	    			  </div>";
	return $jwplayer_embed;
    }


    if ($vidplayer=="youtube"){
       $embedvideo="<object width=\"".$videowidth."\" height=\"".$videoheight."\">
  					<param name=\"movie\" value=\"http://www.youtube.com/v/".$video_ID."&rel=".$ytref."&color1=0x".$ytcolor1."&color2=0x".$ytcolor2."&border=".$ytshowborder."&autoplay=".$ytautoplay."&fs=".$ytfs."&showinfo=".$ytshowinfo."&showsearch=".$ytshowsearch."&hd=".$ythd."\"></param>
  					<param name=\"allowFullScreen\" value=\"true\"></param>
  					<param name=\"quality\" value=\"best\"></param>
  					<embed src=\"http://www.youtube.com/v/".$video_ID."&rel=".$ytref."&color1=0x".$ytcolor1."&color2=0x".$ytcolor2."&border=".$ytshowborder."&autoplay=".$ytautoplay."&fs=".$ytfs."&showinfo=".$ytshowinfo."&showsearch=".$ytshowsearch."&hd=".$ythd."\"
   					 type=\"application/x-shockwave-flash\"
   					 width=\"".$videowidth."\" height=\"".$videoheight."\"
    				 allowfullscreen=\"true\"></embed>
					</object>";
    }
    return $embedvideo;
}

function youtubegeneratevideodownloadlink($video_url){
    $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
    $mosConfig_live_site =  $GLOBALS['mosConfig_live_site'];
    require_once("$mosConfig_absolute_path/administrator/components/com_mytube/includes/mytube_getflv.php");
    $flvlink = api_getflv($video_url);
    return $flvlink;
}

// ###### CONVERT SECONDS TO HH:MM:SS
function sec2hms ($sec, $padHours = false){
    $hms = "";
    $hours = intval(intval($sec) / 3600);
    $hms .= ($padHours)
    ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
    : $hours. ':';
    $minutes = intval(($sec / 60) % 60);
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
    $seconds = intval($sec % 60);
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
    return $hms;
}

?>