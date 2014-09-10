<?php

/**
 * Class view helper format data
 * @author duy.ngo
 */
class Zend_View_Helper_Format extends Zend_View_Helper_Abstract {

    /**
     * this is method contructor for helper
     * @return Zend_View_Helper_Format
     */
    public function format() {
        return $this;
    }

    public function formatDate($date, $format, $timezone = null) {

        //set timezone if $timezone not empty
        if (!empty($timezone))
            date_default_timezone_set($timezone);

        $date = new Zend_Date($date, DATE_FORMAT_DATABASE);
        $dateResult = $date->toString($format);
        date_default_timezone_set('Australia/Brisbane');
        return $dateResult;
    }

    /**
     * format social media feed date
     * @param string $date
     * @param string $format
     * @return string $created
     * @author duy.ngo
     * @since 13-11-2012
     */
    public function formatSocialMediaFeedDate($date, $format = 'dd MMM yyyy') {
        $created = '';
        if (!empty($date) && is_numeric($date)) {

            date_default_timezone_set('America/Los_Angeles');
            $date = new Zend_Date(date('d M Y H:i:s', $date), 'dd-MM-yyyy');
            $today = new Zend_Date(Zend_Date::now(), 'dd-MM-yyyy');

            $created = $date->toString($format);
            if ($date->equals($today))
                $created = 'Today';
            if ($date->equals($today->subDate(1)))
                $created = 'Yesterday';

            date_default_timezone_set('Australia/Brisbane');
        }
        return $created;
    }

    /**
     * convert text to links in twitter feeds
     * @param string $feed
     * @return string $tweetText
     * @author duy.ngo
     * @since 13-11-2012
     */
    public function convertTextToLinkInTwitterFeed($feed) {
        $tweetText = '';
        if (is_string($feed) && !empty($feed)) {
            //convert html special chars to characters
            $tweetText = htmlspecialchars_decode($feed, ENT_NOQUOTES);
            //convert text to normal link
            $tweetText = preg_replace('#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#', '<a class="color-a-customize" href="$1" target="_blank">$1</a>', $tweetText);
            //convert twitter hashTag name to normal link
            $tweetText = preg_replace('/(^|\s)#([a-z0-9_]+)/i', '$1<a class="color-a-customize" href="https://twitter.com/search?q=%23$2&src=hash" target="_blank">#$2</a>', $tweetText);
            //convert mentioned twitter name to normal link
            $tweetText = preg_replace('/(^|\s)@([a-z0-9_]+)/i', '$1<a class="color-a-customize" href="https://twitter.com/$2" target="_blank">@$2</a>', $tweetText);
        }
        return $tweetText;
    }

    /**
     * convert text to links in if in string contain
     * @param string $string
     * @return string $strHaveLink
     * @author Phuc Duong
     * @since 16-11-2012
     */
    public function detectLinkInString($string) {
        $string = preg_replace('/\s\s+/', ' ', $string);
        $content_array = explode(" ", $string);
        $output = '';

        foreach ($content_array as $content) {
//starts with http://
            if (substr(trim($content), 0, 7) == "http://") {
                $content = '<a class="color-a-customize" href="' . $content . '" target="_blank">' . $content . '</a>';
            }
//starts with www.
            if (substr($content, 0, 4) == "www.")
                $content = '<a class="color-a-customize" href="http://' . $content . '" target="_blank">' . $content . '</a>';

            $output .= " " . $content;
        }
        $output = trim($output);
        return $output;
    }

    /**
     * get only domain from link
     * @param string $string
     * @return string $domain
     * @author Phuc Duong
     * @since 19-11-2012
     */
    public function getDomainFromLink($string) {
        $domain = "";
        if (!empty($string)) {
            $listParams = parse_url($string);
            if (!empty($listParams['host'])) {
                $domain = $listParams['host'];
            }
        }
        return $domain;
    }

    /**
     * is show class float left in show button social
     * @param string $linkin
     * @return string $tw
     * @author Phuc Duong
     * @since 19-11-2012
     */
    function isShowClassFB($linkin, $tw) {
        if ((!empty($linkin) && !empty($tw)) || (empty($linkin) && empty($tw))) {
            return true;
        }
    }

    /**
     * show with of frame by device
     * @param device
     * @return string $tw
     * @author Phuc Duong
     * @since 19-11-2012
     */
    function showByDevice($device) {
        $strWidth = "";
        $strClass = "";
        $width = 0;
        $height = 0;
        switch ($device) {
            case "ipad"  :
                $strWidth =  'width="'.DEVICE_IPAD_WITH.'" height="'.DEVICE_IPAD_HEIGHT.'"';
                $strClass = "device-ipad-bg";
                $width = DEVICE_IPAD_WITH; $height = DEVICE_IPAD_HEIGHT;
                break;
            case "iphone":
                $strWidth =  'width="'.DEVICE_IPHONE_WITH.'" height="'.DEVICE_IPHONE_HEIGHT.'"';
                $strClass = "device-iphone";
                $width = DEVICE_IPHONE_WITH; $height = DEVICE_IPHONE_HEIGHT;
                break;
            case "ipadmini"  :
                $strWidth =  'width="'.DEVICE_IPAD_MINI_WITH.'" height="'.DEVICE_IPAD_MINI_HEIGHT.'"';
                $strClass = "device-ipadmini-bg";
                $width = DEVICE_IPAD_MINI_WITH; $height = DEVICE_IPAD_MINI_HEIGHT;
                break;
            case "androidtablet"  :
                $strWidth =  'width="'.DEVICE_ANDROID_TABLET_WITH.'" height="'.DEVICE_ANDROID_TABLET_HEIGHT.'"';
                $strClass = "device-androidtablet";
                $width = DEVICE_ANDROID_TABLET_WITH; $height = DEVICE_ANDROID_TABLET_HEIGHT;
                break;
            case "androidphone"  :
                $strWidth =  'width="'.DEVICE_ANDROID_PHONE_WITH.'" height="'.DEVICE_ANDROID_PHONE_HEIGHT.'"';
                $strClass = "device-androidphone";
                $width = DEVICE_ANDROID_PHONE_WITH; $height = DEVICE_ANDROID_PHONE_HEIGHT;
                break;
        }
        return array("width"=>$strWidth, "class"=>$strClass, "size" => array($width, $height));
    }

}