<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script src="<?php echo JURI::base() ?>components/com_mytube/assets/js/mytubeajax.js" language="javascript"></script>

<script>
function selectCategory(catid) {
    var link = "index.php?option=com_mytube&task=categories&catid=" + catid.value;
    location.href = link;
}
</script>

<div class="mytube_wrapper" style="float: left; width: 100%;">
        <div style="padding-top: 10px;">
        <a href="<?php echo JRoute::_("index.php?view=videos")?>">Main</a>
        <?php foreach ($this->breadcrum as $cat):?>
        <img src="components/com_mytube/assets/images/arrow.png">
                <a href="<?php echo JRoute::_("index.php?task=categories&catid=$cat->id")?>"><?php echo $cat->category_name?></a>
        <?php endforeach;?>
        </div>
        <div id="mytube_container" name="mytube">
                <!-- ###################### VIDEO PLAYER START ################-->
                <div id="watch-vid-title-container">
                        <div id="watch-vid-title" style="width:<?php echo $this->c->video_player_width;?>px;">
                                <?php echo stripslashes($this->row->video_title);?>
                        </div>
                        <div id="watch-vid-category">
                                Select Category:<br><?php echo $this->parentSelect ?>
                        </div>
                </div>

                <div id="watch-this-vid">
                        <?php include("video_player.php");?>
                </div>
                <!-- ###################### VIDEO PLAYER END ################-->

                <!-- ###################### VIDEO RIGHT COLUMN START ################-->
                <div id="watch-other-vids">

                        <!-- ###################### AJAX TABS START ################-->

                                <script type="text/javascript">
                                var live_site = '<?php echo JURI::base() ?>';
                                window.addEvent('domready', function(){
                                    $('category').addEvent('click', function(e) {
                                        e = new Event(e).stop();
                                        $('category').addClass('selected');
                                        $('newest').removeClass('selected');
                                        $('member').removeClass('selected');
                                        $('featured').removeClass('selected');
                                        var timenow = Date();
                                        var url = live_site + 'components/com_mytube/ajax.mytube.php?task=aio&act=category&catid=<?php echo $this->row->category_id ?>'+ '&timenow=' + timenow;
                                        new Ajax(url, {method: 'post', update: $('video_lists'), evalScripts: true}).request();
                                    });
                                });

                                window.addEvent('domready', function(){
                                    $('member').addEvent('click', function(e) {
                                        e = new Event(e).stop();
                                        $('member').addClass('selected');
                                        $('newest').removeClass('selected');
                                        $('category').removeClass('selected');
                                        $('featured').removeClass('selected');
                                        var timenow = Date();
                                        var url = live_site + 'components/com_mytube/ajax.mytube.php?task=aio&act=member&userid=<?php echo $this->row->user_id ?>'+ '&timenow=' + timenow;
                                        new Ajax(url, {method: 'get', update: $('video_lists'), evalScripts: true}).request();
                                    });
                                });

                                window.addEvent('domready', function(){
                                    $('newest').addEvent('click', function(e) {
                                        e = new Event(e).stop();
                                        $('newest').addClass('selected');
                                        $('category').removeClass('selected');
                                        $('member').removeClass('selected');
                                        $('featured').removeClass('selected');
                                        var timenow = Date();
                                        var url = live_site + 'components/com_mytube/ajax.mytube.php?task=aio&act=newest&catid=<?php echo $this->row->category_id ?>'+ '&timenow=' + timenow;
                                        new Ajax(url, {method: 'get', update: $('video_lists'), evalScripts: true}).request();
                                    });
                                });

                                window.addEvent('domready', function(){
                                    $('featured').addEvent('click', function(e) {
                                        e = new Event(e).stop();
                                        $('featured').addClass('selected');
                                        $('category').removeClass('selected');
                                        $('member').removeClass('selected');
                                        $('newest').removeClass('selected');
                                        var timenow = Date();
                                        var url = live_site + 'components/com_mytube/ajax.mytube.php?task=aio&act=featured&catid=<?php echo $this->row->category_id ?>'+ '&timenow=' + timenow;
                                        new Ajax(url, {method: 'get', update: $('video_lists'), evalScripts: true}).request();
                                    });
                                });

                        </script>
                                <div class="category-tabs">
                    <a id="category" href="#" class="selected"><?php echo _SUBTABS_CATEGORY ?></a>
                    <a id="member" href="#"><?php echo _SUBTABS_MEMBER ?></a>
                    <a id="newest" href="#"><?php echo _SUBTABS_LASTEST ?></a>
                    <a id="featured" href="#"><?php echo _SUBTABS_FEATURED ?></a>
                                </div>

                        <!-- ###################### AJAX TABS END ################-->
                        <div class="clearL"></div>
                        <div id="tab-container">
                        <!--############## CATEGORY LIST ENTRY START ###########-->
                        <div id="video_lists">
                                <div class="watch-discoverbox">
                                <?php if ($this->videosincategory):?>
                                        <?php foreach ($this->videosincategory as $video):?>
                                                <div class="video-entry">
                                                        <div class="v90WideEntry">
                                                                <div class="v90WrapperOuter">
                                                                        <div class="v90WrapperInner">
                                                                        <?php if ($video->video_type == 'local' || $video->video_type == ''):?>
                                                                        <a href="<?php echo JRoute::_("index.php?view=video&id=$video->id")?>" id="video-url-e8FGIveLwyw">
                                                                <img alt="<?php echo $video->video_title?>" qlicon="e8FGIveLwyw" class="vimg90" src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -1).$video->directory?>/_thumbs/<?php echo $video->video_thumb; ?>" border="0" />
                                                                        </a>
                                                                        <?php else:?>
                                                                        <a href="<?php echo JRoute::_("index.php?view=video&id=$video->id")?>" id="video-url-e8FGIveLwyw">
                                                                <img alt="<?php echo $video->video_title?>" qlicon="e8FGIveLwyw" class="vimg90" src="<?php echo $video->video_thumb; ?>" border="0" />
                                                                        </a>
                                                                        <?php endif;?>
                                                                                <div class="video-time-player"><span><?php echo $video->duration?></span>
                                                                                </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="video-main-content">
                                                        <div class="video-mini-title"><a href="<?php echo JRoute::_("index.php?view=video&id=$video->id")?>" title="<?php echo $video->video_title?>"><?php echo stripslashes($video->video_title)?></a>
                                                        </div>
                                                        <div class="video-view-count"><?php echo $video->hits?> <?php echo strtolower(_VIDEOINFO_NUMBER_VIEWS) ?>
                                                        </div>
                                                        <div class="video-username"><?php echo _VIDEOINFO_AUTHOR ?>: <a href="<?php echo JRoute::_("index.php?view=videos&type=member&user_id=" . $video->user_id) ?>"><?php echo mytube::showShortAuthor($video->username)?></a>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="watch-discoverbox-divider"/>
                                        </div>
                                        <?php endforeach;?>
                                        <?php if ($this->countvideosincategory > 20):?>
                                        <center><a href="<?php echo JRoute::_("index.php?view=videos&category_id=". $this->row->category_id)?>">See all <?php echo $this->countvideosincategory?> videos</a></center>
                                        <?php endif;?>
                                        <?php endif;?>
                        </div>
                        <!--############## CATEGORY LIST ENTRY END ###########-->
                        </div>
                </div>
                <!--############## CUSTOM MODULE POSITION ###########-->
                <div style="float: left; width: 324px; border: 0px solid #CCCCCC; margin-top: 10px;"?>
                      <?php
                      $mytube_player_modules = &JModuleHelper::getModules('mytube_player');
                      foreach ($mytube_player_modules as $player_module) {
                          echo "<div id=\"module-padding\">";
                          echo JModuleHelper::renderModule($player_module);
                          echo "</div>";
                      }
                        ?>
                </div>
                <!--############## CUSTOM MODULE POSITION END ###########-->
        </div>
        <div class="clearL"></div>
                <div id="watch-ratings-views" style="width:<?php echo $this->c->video_player_width;?>px;">
<!---------------- RATINGS AND VIEWS CONTAINER --------------------->
                        <div id="ratings-container">

                                <?php if ($this->c->allow_ratings): ?>
                                <span id="ajaxRating" class="mytube_ajaxrating">

                                <?php
                                echo _VIDEOINFO_RATE . " ";

                                $emptyStar = JURI::base() . "components/com_mytube/assets/images/emptyStar16x16.gif";
                                $halfStar = JURI::base() . "components/com_mytube/assets/images/halfStar16x16.gif";
                                $filledStar = JURI::base() . "components/com_mytube/assets/images/fullStar16x16.gif";

                                $starMap = '00000';
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    $starMap[$i - 1] = floor($this->row->ratingAvg) >= $i ? '2' : (round($this->row->ratingAvg) == $i ? '1' : '0');
                                }

                                if ($this->row->hits == 0) {
                                    echo "<span class=\"mytube_ratingStars\" title=\"Not yet rated\">";
                                } else {
                                    echo "<span class=\"mytube_ratingStars\" title=\"".round($this->row->ratingAvg, 1) ." out of 5 stars\">";
                                }

                                $starMapRate = '00000';
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    $star = floor($this->row->ratingAvg) >= $i ? $filledStar : (round($this->row->ratingAvg) == $i ? $halfStar : $emptyStar);
                                    $starMapRate[$i - 1] = '2';

                                    if ($this->user_id != "")
                                    {
                                        echo "<a id=\"mytube_rate_href\""
                                        ." onClick=\"mytubeAJAX.prototype.rateVideo(".$this->row->id.", ".$this->user_id.",".$i."); return false;\""
                                        ." onMouseOver=\"javascript: highlightStars('".$starMapRate."', '".$filledStar."', '".$halfStar."', '".$emptyStar."');\" "
                                        ." onMouseOut=\"javascript: highlightStars('".$starMap."', '".$filledStar."', '".$halfStar."', '".$emptyStar."');\" "
                                        ." href=\"#mytube\">"
                                        ."<img src=\"".$star."\" id=\"videoStar".$i."\" vspace=\"0\" hspace=\"0\" height=\"16\" width=\"16\" border=\"0\">"
                                        ."</a>";
                                    }
                                    else
                                    {
                                        echo "<img src=\"".$star."\" id=\"videoStar".$i."\" vspace=\"0\" "
                                        ."hspace=\"0\" height=\"16\" width=\"16\" border=\"0\" />";
                                    }
                                }
                                echo "</span>";


                                if ($this->user_id == "")
                                echo "<span id=\"ratingThanks\" class=\"mytube_ratingThanks\"></span><span class=\"mytuberatingcount\">Sign in to rate</span>";
                                else if ($this->row->ratingCount == 1)
                                echo "<span id=\"ratingThanks\" class=\"mytube_ratingThanks\"></span><span class=\"mytuberatingcount\">1 rating</span>";
                                else if ($this->row->ratingCount)
                                echo "<span id=\"ratingThanks\" class=\"mytube_ratingThanks\"></span><span class=\"mytuberatingcount\">Not yet rated</span>";
                                else
                                echo "<span id=\"ratingThanks\" class=\"mytube_ratingThanks\"></span><span class=\"mytuberatingcount\">".$this->row->ratingAvg." ratings</span>";

                                ?>
                                </span>
                        <?php endif;?>
                        <div id="video-views">
                        <span class="mytube_views"><strong>Views:</strong> <?php echo $this->row->hits == 0 ? 1 : $this->row->hits; ?></span>
                        </div>

                        </div>

                        <div id="vidinfocontainer">
<!---------------- AUTHOR DURATION CONTAINER --------------------->
                            <div id="author-container">
                                    <div id="video-author">
                                            <strong><?php echo _VIDEOINFO_AUTHOR ?>: </strong><span class="grayText"><a href="<?php echo JRoute::_("index.php?view=videos&type=member&user_id=" . $this->row->user_id) ?>"><?php echo $this->row->username;?></a></span><br />
                                            <strong><?php echo  _VIDEOINFO_DATE_ADDED ?>: </strong><span class="grayText"><?php echo $this->row->date_added;?></span><br />
                                    </div>
                                    <div id="duration-category">
                                            <strong><?php echo _VIDEOINFO_CATEGORY ?>: </strong><span class="grayText"><a href="<?php echo JRoute::_("index.php?view=videos&category_id=" . $this->row->category_id)?>"><?php echo $this->row->category_name;?></a></span><br />
                                            <strong><?php echo _VIDEOINFO_DURATION ?>: </strong><span class="grayText"><?php echo $this->row->duration;?></span>
                                    </div>
                            </div>
<!---------------- DESCRIPTION AND KEYWORDS CONTAINER --------------------->
                        <div id="description-container">
                                <div id="video-tags">
                                        <strong><?php echo _VIDEOINFO_TAGS ?>:</strong> <?php echo stripslashes($this->videotaglinked);?>
                                </div>
                                <div id="video-desc">
                                        <strong><?php echo _VIDEOINFO_DESCRIPTION ?>: </strong><?php echo str_replace("\r\n", "<br/>", stripslashes($this->row->video_desc));?>
                                </div>
                        </div>
<!---------------- EMBED AND LINK CONTAINER --------------------->
                        <div id="embed-container">
                                <div id="video-link">
                                        <label for="link_to_page"><strong><?php echo _VIDEOINFO_LINK ?>: </strong></label>
                                        <?php $mosConfig_live_site = substr(JURI::base(), 0, strlen(JURI::base()) -1);?>
                                        <input class="videodirectlink" type="text" id="link_to_page" name="link_to_page" value="<?php echo $mosConfig_live_site?>/index.php?option=com_mytube&view=video&id=<?php echo $this->row->id?>" onClick="javascript:this.select();" />
                                </div>
                                 <?php if ($this->c->show_embed_code):?>
                                <div id="video-embed">
                                        <label for="embed_video"><strong><?php echo _VIDEOINFO_EMBED ?>: </strong></label>
                                        <input class="videoembedcode" type="text" id="embed_video" name="embed_video" value="<embed  src='<?php echo $mosConfig_live_site?>/components/com_mytube/assets/swf/player.swf' width='560' height='441' allowscriptaccess='always' allowfullscreen='true' flashvars='height=441&width=560&file=<?php echo $mosConfig_live_site?><?php echo $this->row->directory?>/<?php echo $this->row->video_url?>&autostart=true'  />" onClick="javascript:this.select();" />
                                </div>
                                <?php endif;?>
                        </div>
                        </div>
                        <div style="clear: both;"></div>
                             <?php if ($this->user->usertype == "Administrator" || $this->user->usertype == "Super Administrator"):?>
                                        Options:
                                        <a href="<?php echo $this->basUrl?>/administrator/index.php?option=com_mytube&controller=videos&task=edit&cid[]=<?php echo $this->row->id?>" target="_blank">Edit</a>
                                        | <a onclick="return confirm('Are You Sure?')" href="<?php echo $this->basUrl?>/administrator/index.php?option=com_mytube&controller=videos&task=remove&cid[]=<?php echo $this->row->id?>" target="_blank">Delete</a>
                                                </p>
                                <?php endif;?>
                        <div style="clear: both;"></div>

                        <?php if ($this->c->allow_comment):?>
                        <div class="mytube_comments" style="width:<?php echo $this->c->video_player_width;?>px;">
                            <?php  echo $this->mytubeComments; ?>
                        </div>
                        <?php endif;?>
        </div>
</div>
<div style="clear: both;"></div>