<?php

/**
 * Class view helper common function
 * @author tri.van
 * @since Mon Now 19, 10:30 AM
 */
class Zend_View_Helper_Common extends Zend_View_Helper_Abstract {

    /**
     * this is method contructor for helper
     * @return Zend_View_Helper_Common
     */
    public function common() {
        return $this;
    }

    /**
     * get config value
     * @author tri.van
     * @param string $configCode
     * @return string $configValue
     */
    public function getConfig($configCode) {
        $modelConfig = new Application_Model_Configs();
        $configValue = $modelConfig->getConfigValue($configCode);
        return $configValue;
    }

    /**
     * get config values by category code
     * @author tri.van
     * @param string $categoryCode
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public function getConfigByCategoryCode($categoryCode) {
        $modelConfig = new Application_Model_Configs();

        return $modelConfig->getConfigValueByCategoryCode($categoryCode);
    }

    /**
     * get user browser
     * @author tri.van
     * @return string $ub - your browser
     */
    function getUserBrowser() {
        $uAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $ub = '';
        if (preg_match('/MSIE/i', $uAgent)) {
            $ub = "ie";
        } elseif (preg_match('/Firefox/i', $uAgent)) {
            $ub = "firefox";
        } elseif (preg_match('/Chrome/i', $uAgent)) {
            $ub = "chrome";
        } elseif (preg_match('/Safari/i', $uAgent)) {
            $ub = "safari";
        } elseif (preg_match('/Opera/i', $uAgent)) {
            $ub = "opera";
        } elseif (preg_match('/Flock/i', $uAgent)) {
            $ub = "flock";
        }
        return $ub;
    }

    /**
     * pre load image crazy egg
     * @author tri.van
     * @return array
     * @since Thu Now 29, 9:51AM
     */
    public function getImageCrazyEgg() {
        $storeModel = new Application_Model_StoreValues();
        $storeContent = $storeModel->getStoreValueByType("crazy-egg-api");
        if (count($storeContent))
            $snapShots = unserialize($storeContent->Content);
        else {
            $snapShots["screenshot_url"] = null;
            $snapShots["heatmap_url"] = null;
        }
        return $snapShots;
    }

    public function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}