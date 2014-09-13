<?php

/**
 * @desc helper common
 * @author tri.van
 * @since Web Now 14, 9:47 AM
 */
class Zend_Controller_Action_Helper_Common extends Zend_Controller_Action_Helper_Abstract {

    /**
     * constructor of helper
     * @since Web Now 14, 9:47 AM
     */
    public function direct(){
    }

    /**
     * get config value
     * @author tri.van
     * @param string $configCode
     * @return string $configValue
     */
    public function getConfig($configCode){
        $modelConfig = new Application_Model_Configs();
        $configValue = $modelConfig->getConfigValue($configCode);
        return $configValue;
    }

    /**
     * get config values by category code
     * @author quang.nguyen
     * @param string $categoryCode
     * @return Zend_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public function getConfigByCategoryCode($categoryCode){
        $modelConfig = new Application_Model_Configs();

        return $modelConfig->getConfigValueByCategoryCode($categoryCode);
    }

}