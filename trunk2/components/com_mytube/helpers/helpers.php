<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined('_JEXEC') or die('Restricted access');

/**
 * Holds some usefull functions to keep the code a bit cleaner
 */
class JTHelper {
    /**
     * Return extension of a file name
     *
     * @param unknown_type $filename
     * @return unknown
     */
    public function getFileExt($filename) {
        $names = explode('.', $filename);
        $i = count($names);
        if ($i>0) return  strtolower($names[$i-1]); else return '';
    }

    /**
     * return safe file name (lower byte character)
     *
     * @param unknown_type $filename
     */
    public function getSafefileName($filename) {
        $safe = '';
        for ($i=0, $n=strlen($fileName); $i<$n; $i++) {
            $c = $fileName[$i];
            if (ord($c) < 128 && ord($c) > 32)
            $safe .= $c;
        }
        if ($safe=='') $safe = '0';
        return $safe;
    }

    /**
     * Check hard disk for not exist fileName, if file exists, change it name
     *
     * @param unknown_type $pathName
     * @param unknown_type $fileName
     * @param unknown_type $extName
     */
    public function getNoDuplicateFileName($pathName, &$fileName, $extName) {
        $i = 0;
        $newFileName = $fileName;
        while (file_exists("$pathName/$newFileName.$extName")) {
            $newFileName = $fileName . (++$i);
        }
        $fileName = $newFileName;
    }

    public  function removeSpaceFileName($fileName) {
        return str_replace(" ", "_", $fileName);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $original_file
     * @param unknown_type $new_file
     * @param unknown_type $c  : config oblject
     * @return unknown
     */
    public  function movieToFlv($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpeg_command = "$c->ffmpeg_path -y -i $path_original -ab $c->convert_audio_bitrate*1000 -ar 22050 -b $c->convert_video_bitrate*1000 -s $c->convert_frame_size $path_new";
        return exec("$ffmpeg_command 2>&1");
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $original_file
     * @param unknown_type $new_file
     * @param unknown_type $c  : config oblject
     * @return unknown
     */
    public  function movieToMp4H264($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpegpath = $c->ffmpeg_path;

        // Define standard video ratios, as used within the television and motion picture industries
        // To be consistent, round everything to 2-decimal places so all numbers are in the same format
        $broadcastTV = round(4/3, 2); // the aspect ratio is 4:3, which is 640/480; rounded to two decimals, this is 1.33
        $widescreeenTV = round(16/9, 2); //the aspect ratio is 16:9, which is 640/360; rounded to two decimals, this is 1.78
        $cinemaScope = 2.35; //the aspect ratio is 2.35:1, which is roughly 640/272 rounded to two decimal places

        // Let's set our absolute dimensions, as dicated by IPod and other external media players

        $maxWidth = 640; // per IPod specs (and other similar players), 640 is maximum width, period
        $wideMaxHeight = 360; //this number keeps the proper Widescreen aspect ratio when coupled with IPod's absolute maximum width of 640
        $cinemaMaxHeight = 272; // this is the proper number for proper CinemaScope ratio coupled with IPod's absolute maximum width of 640
        $standardMaxHeight = 480; // this is the standard height for broadcast ratio, and the largest video height Ipod can handle.

        $movie = new ffmpeg_movie($path_original);
        $fcodec = $movie->getVideoCodec();
        // Now, check to see the size of the original video to make sure we're not enlarging something smaller than the max dimensions...
        $vidWith = $movie->getFrameWidth();
        $vidHeight =  $movie->getFrameHeight();

        // Set up the encode variables that will actually be passed to the encoder.
        // Remember, our absolute maximum width is 640, no matter what the aspect ratio is
        if ($vidWidth >= $maxWidth) {
            $encodeWidth = $maxWidth;
        } else {
            $encodeWidth = $vidWidth;
        }
        //						if ($encodeWidth == '')
        //							$encodeWidth = $maxWidth;
        // Because video height is what determines the ratio, we have to compare actual video height to each of the three standard types
        // First, get the aspect ratio for the current movie - remember to round it so it's in two-decimal format like the other values
        $vidRatio = round($vidWidth/$vidHeight, 2);

        // Check to see if the video has a Widescreen or Cinematic ratio. If the video for some reason is neither Widescreen nor Cinematic,
        // then let's just use Standard ratio.
        $encodeHeight = $vidHeight; // Unless of course we have to adjust the height; see code below

        switch($vidRatio) {
            case ($vidRatio == $widescreenTV):
                if ($encodeHeight >= $wideMaxHeight) {$encodeHeight = $wideMaxHeight;}
                break;
            case ($vidRatio == $cinemaScope):
                if ($encodeHeight >= $cinemaMaxHeight) {$encodeHeight = $cinemaMaxHeight;}
                break;
            default: // This is Broadcast TV, which is standard 640x480 ratio
            if ($encodeHeight >= $standardMaxHeight) {$encodeHeight = $standardMaxHeight;}
        }

        // Now, we can build the correct WxH (width x Height) ratio variable and pass this to the encode instructions.

        $encodeRatio = $encodeWidth."x".$encodeHeight;
        //echo $encodeRatio; exit();
        if ($c->h264_quality == 'highest') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 150k -pass 2 -s 640x352 -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 2 -s 640x480 -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 = "/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }
        if ($mp4_quality == 'default') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s 640x352 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 2 -s 640x480 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder ="$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 ="$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 ="/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }
        if ($mp4_quality == 'lowest') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 1 -s 640x352 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s 640x480 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder ="$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 ="$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 ="/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }

        return exec("$cmd_mencoder 2>&1");
    }

    public  function movieToMp4H264_alduccino($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpegpath = $c->ffmpeg_path;
        $ffmpeg_command = "$ffmpegpath -i $path_original -acodec libfaac -ab 128k -ac 2 -vcodec libx264 -vpre lossless_medium -crf 22 -threads 0 -s 600x388 $path_new.mp4";
        exec("$ffmpeg_command 2>&1");

        return exec("/usr/bin/qt-faststart $path_new.mp4 $path_new");
        unlink("$path_new.mp4");
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $video_path
     * @param unknown_type $thumb_path
     * @param unknown_type $width
     * @param unknown_type $height
     * @param unknown_type $time
     * @param unknown_type $c
     * @return unknown
     */
    public function flvToThumbnail($video_path, $thumb_path, $width=120, $height=90, $time=0, $c) {
        $ffmpeg_path = $c->ffmpeg_path;
        if ($time<10)
        $command = "$ffmpeg_path -y -itsoffset -2 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
        else
        $command = "$ffmpeg_path -y -itsoffset -10 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
        $command .= " 2>&1";
        //echo $command; exit();
        return @exec($command, $output);
    }

    /**
     * Get movie duration by php-ffmpeg
     *
     * @param unknown_type $filename
     */
    public function getMovieDuration($filename) {
        ini_set("max_execution_time",300000);
        $video_info = @ new ffmpeg_movie($filename); //duration of new flv file.
        $sec = $video_info->getDuration(); // Gets the duration in secs.
        //continue with rest of process
        if ($sec == "" || !is_numeric($sec)) {
            $sec = 2;
        }
        return $sec;
    }

    /**
     * Load Vertical Module in mytube component
     *
     * @param unknown_type $modulePosition
     * @param unknown_type $width
     * @param unknown_type $type: 'left' ore 'right'
     */
    public function loadCustomVerticalModule($modulePosition, $width, $type ) {
        $modules = &JModuleHelper::getModules($modulePosition);
        if ($width != '') {
            echo '<div id="video'.$type.'-column" style="min-height:140px; width: '.$width.'px">';
            foreach ($modules as $module) {
                echo "<div id=\"mytube-module-box\">";
                if($module->showtitle=="1") {
                    echo "<div id=\"mytube-module-title\">";
                    echo $module->title;
                    echo "</div>";
                }
                echo "<div id=\"mytube-module-content\">";
                echo JModuleHelper::renderModule($module);
                echo "</div>";
                echo "</div>";
            }
            echo '</div>';
        }
    }

    public function showLocalThumbnail($thumb_file) {
        if (!is_dir(JPATH_BASE.$thumb_file)) {
            if (file_exists(JPATH_SITE.$thumb_file)) {
                echo "<img class='vimg120' src='".JURI::root( true ).$thumb_file."' border='0'/>";
            } else {
                echo "<img class='vimg120' src='".JURI::root( true )."/administrator/components/com_mytube/assets/images/no-thumbnail.jpg' border='0'/>";
            }
        } else {
            echo "<img class='vimg120' src='".JURI::root( true )."/administrator/components/com_mytube/assets/images/no-thumbnail.jpg' border='0'/>";
        }
    }

    public function showLocalThumbnailAjax($thumb_file) {
        $root = substr(JURI::root( true ), 0, strlen(JURI::root( true ))-24);
        if (!is_dir(JPATH_BASE.$thumb_file)) {
            if (file_exists(JPATH_SITE.$thumb_file)) {
                echo "<img class='vimg120' src='".$root.$thumb_file."' border='0'/>";
            } else {
                echo "<img class='vimg120' src='".$root."/administrator/components/com_mytube/assets/images/no-thumbnail.jpg' border='0'/>";
            }
        } else {
            echo "<img class='vimg120' src='".$root."/administrator/components/com_mytube/assets/images/no-thumbnail.jpg' border='0'/>";
        }
    }
    /**
    * Convert seconds to HOURS:MINUTES:SECONDS format
    **/
    public function sec2hms ($sec, $padHours = false)
    {

        // holds formatted string
        $hms = "";

        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600);

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours)
        ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
        : $hours. ':';

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in
        // minutes past the hour: to get that, we need to
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60);

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

        // seconds are simple - just divide the total
        // seconds by 60 and keep the remainder
        $seconds = intval($sec % 60);

        // add to $hms, again with a leading 0 if needed
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // done!
        return $hms;
    }

    /**
     * Convert from UTF-8 to non mark Vietnamese text
     *
     * @param String $value: input text
     * @return Non marke Vietnamese text
     */
    public static function vietDecode($value)
    {
        #---------------------------------a^
        $value = str_replace("áº¥", "a", $value);
        $value = str_replace("áº§", "a", $value);
        $value = str_replace("áº©", "a", $value);
        $value = str_replace("áº«", "a", $value);
        $value = str_replace("áº­", "a", $value);
        #---------------------------------A^
        $value = str_replace("áº¤", "A", $value);
        $value = str_replace("áº¦", "A", $value);
        $value = str_replace("áº¨", "A", $value);
        $value = str_replace("áºª", "A", $value);
        $value = str_replace("áº¬", "A", $value);
        #---------------------------------a(
        $value = str_replace("áº¯", "a", $value);
        $value = str_replace("áº±", "a", $value);
        $value = str_replace("áº³", "a", $value);
        $value = str_replace("áºµ", "a", $value);
        $value = str_replace("áº·", "a", $value);
        #---------------------------------A(
        $value = str_replace("áº®", "A", $value);
        $value = str_replace("áº°", "A", $value);
        $value = str_replace("áº²", "A", $value);
        $value = str_replace("áº´", "A", $value);
        $value = str_replace("áº¶", "A", $value);
        #---------------------------------a
        $value = str_replace("Ã¡", "a", $value);
        $value = str_replace("Ã?", "a", $value);
        $value = str_replace("áº£", "a", $value);
        $value = str_replace("Ã£", "a", $value);
        $value = str_replace("áº¡", "a", $value);
        $value = str_replace("Ã¢", "a", $value);
        $value = str_replace("Ä?", "a", $value);
        #---------------------------------A
        $value = str_replace("Ã?", "A", $value);
        $value = str_replace("Ã?", "A", $value);
        $value = str_replace("áº¢", "A", $value);
        $value = str_replace("Ã?", "A", $value);
        $value = str_replace("áº?", "A", $value);
        $value = str_replace("Ã?", "A", $value);
        $value = str_replace("Ä?", "A", $value);
        #---------------------------------e^
        $value = str_replace("áº¿", "e", $value);
        $value = str_replace("á»?", "e", $value);
        $value = str_replace("á»?", "e", $value);
        $value = str_replace("á»?", "e", $value);
        $value = str_replace("á»?", "e", $value);
        #---------------------------------E^
        $value = str_replace("áº¾", "E", $value);
        $value = str_replace("á»?", "E", $value);
        $value = str_replace("á»?", "E", $value);
        $value = str_replace("á»?", "E", $value);
        $value = str_replace("á»?", "E", $value);
        #---------------------------------e
        $value = str_replace("Ã©", "e", $value);
        $value = str_replace("Ã¨", "e", $value);
        $value = str_replace("áº»", "e", $value);
        $value = str_replace("áº½", "e", $value);
        $value = str_replace("áº¹", "e", $value);
        $value = str_replace("Ãª", "e", $value);
        #---------------------------------E
        $value = str_replace("Ã?", "E", $value);
        $value = str_replace("Ã?", "E", $value);
        $value = str_replace("áºº", "E", $value);
        $value = str_replace("áº¼", "E", $value);
        $value = str_replace("áº¸", "E", $value);
        $value = str_replace("Ã?", "E", $value);
        #---------------------------------i
        $value = str_replace("Ã­", "i", $value);
        $value = str_replace("Ã¬", "i", $value);
        $value = str_replace("á»?", "i", $value);
        $value = str_replace("Ä©", "i", $value);
        $value = str_replace("á»?", "i", $value);
        #---------------------------------I
        $value = str_replace("Ã?", "I", $value);
        $value = str_replace("Ã?", "I", $value);
        $value = str_replace("á»?", "I", $value);
        $value = str_replace("Ä¨", "I", $value);
        $value = str_replace("á»?", "I", $value);
        #---------------------------------o^
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        #---------------------------------O^
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("Ã?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        #---------------------------------o*
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("á»¡", "o", $value);
        $value = str_replace("á»£", "o", $value);
        #---------------------------------O*
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("á»¢", "O", $value);
        #---------------------------------u*
        $value = str_replace("á»©", "u", $value);
        $value = str_replace("á»«", "u", $value);
        $value = str_replace("á»­", "u", $value);
        $value = str_replace("á»¯", "u", $value);
        $value = str_replace("á»±", "u", $value);
        #---------------------------------U*
        $value = str_replace("á»¨", "U", $value);
        $value = str_replace("á»ª", "U", $value);
        $value = str_replace("á»¬", "U", $value);
        $value = str_replace("á»®", "U", $value);
        $value = str_replace("á»°", "U", $value);
        #---------------------------------y
        $value = str_replace("Ã½", "y", $value);
        $value = str_replace("á»³", "y", $value);
        $value = str_replace("á»·", "y", $value);
        $value = str_replace("á»¹", "y", $value);
        $value = str_replace("á»µ", "y", $value);
        #---------------------------------Y
        $value = str_replace("?", "Y", $value);
        $value = str_replace("á»²", "Y", $value);
        $value = str_replace("á»¶", "Y", $value);
        $value = str_replace("á»¸", "Y", $value);
        $value = str_replace("á»´", "Y", $value);
        #---------------------------------DD
        $value = str_replace("Ä?", "D", $value);
        $value = str_replace("Ä?", "D", $value);
        $value = str_replace("Ä?", "d", $value); #Supplemental

        #---------------------------------o
        $value = str_replace("Ã³", "o", $value);
        $value = str_replace("Ã²", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("Ãµ", "o", $value);
        $value = str_replace("á»?", "o", $value);
        $value = str_replace("Ã´", "o", $value);
        $value = str_replace("Æ¡", "o", $value);
        #---------------------------------O
        $value = str_replace("Ã?", "O", $value);
        $value = str_replace("Ã?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("Ã?", "O", $value);
        $value = str_replace("á»?", "O", $value);
        $value = str_replace("Ã?", "O", $value);
        $value = str_replace("Æ?", "O", $value);
        #---------------------------------u
        $value = str_replace("Ãº", "u", $value);
        $value = str_replace("Ã¹", "u", $value);
        $value = str_replace("á»§", "u", $value);
        $value = str_replace("Å©", "u", $value);
        $value = str_replace("á»¥", "u", $value);
        $value = str_replace("Æ°", "u", $value);
        #---------------------------------U
        $value = str_replace("Ã?", "U", $value);
        $value = str_replace("Ã?", "U", $value);
        $value = str_replace("á»¦", "U", $value);
        $value = str_replace("Å¨", "U", $value);
        $value = str_replace("á»¤", "U", $value);
        $value = str_replace("Æ¯", "U", $value);
        $value = str_replace(array('"', "'", '/', '\\', '?', ',', ':', '|', '>', '<'), '', $value);
        return $value;
    }

    function SizeImage($maxWidth, $maxHW, $fileName, $thumbFileName) {
        list($width, $height, $type, $attr) = @getimagesize($fileName);
        if ($width==null || $width==0) return $fileName;

        if ($width>$maxWidth) {
            $scaleX = $maxWidth / $width;
            $scaleY = $maxHW / $height;
            if ($scaleX > $scaleY) $scaleX = $scaleY;
            $maxWidth = $width * $scaleX;
            $maxHeight = $height * $scaleX;
            $img2 = imagecreatetruecolor($maxWidth, $maxHeight);
            switch ($type) {
                case 1:
                    $img = imagecreatefromgif($fileName);
                    break;
                case 2:
                    $img = imagecreatefromjpeg($fileName);
                    break;
                case 3:
                    $img = imagecreatefrompng($fileName);
                    break;
            }
            imagecopyresampled($img2, $img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
            imagejpeg($img2, $thumbFileName);
        }
    }
}
?>