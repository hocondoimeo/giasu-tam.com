<?php

/**
 * Model for config
 * @author tri.van
 */
class Application_Model_Configs extends Application_Model_Abstract {
    /**
     * Instance table name
     * @var type
     */
    protected $_name = 'Configs';
    /**
     * Instance primary key in config
     */
    protected $_primary = 'ConfigId';

    /**
     * get config value
     * @author tri.van
     * @param string $configcode
     * @return string Config Value
     * @since Tue Now 12, 9:48 AM
     */
    public function getConfigValue($configcode){
        $select = $this->select()->from($this,'ConfigValue')->where('ConfigCode = ?',$configcode)->where('IsDisabled = 0');
        $result = $this->fetchRow($select);
        if($result) return $result->ConfigValue;
        else return null;
    }
    
    /**
     * get config value
     * @author tri.van
     * @param string $configcode
     * @return string Config Value
     * @since Tue Now 12, 9:48 AM
     */
    public function getConfigName($configcode){
    	$select = $this->select()->from($this,'ConfigName')->where('ConfigCode = ?',$configcode)->where('IsDisabled = 0');
    	$result = $this->fetchRow($select);
    	if($result) return $result->ConfigName;
    	else return null;
    }
    
    public function getConfigDetail($configcode){
    	$cols = array('ConfigValue', 'ConfigName');
    	$select = $this->select()->from($this, $cols)->where('ConfigCode = ?',$configcode)->where('IsDisabled = 0');
    	$result = $this->fetchRow($select);
    	if($result) return $result;
    	else return null;
    }

    /**
     * get config value
     * @author Phuc Duong
     * @param string $configcode
     * @return string Config Value
     * @since Tue Now 12, 9:48 AM
     */
    public function getConfigValueByCategoryCode($categoryCode){
        if(empty($categoryCode)){
            return null;
        }
        $select = $this->select()
                       ->from('Configs')
                       ->join('ConfigCategories', 'Configs.ConfigCategoryId = ConfigCategories.ConfigCategoryId')
                       ->setIntegrityCheck(false)
                       ->where('ConfigCategoryCode = ?',$categoryCode)
                       ->where('ConfigCategories.IsDisabled = 0')
                       ->where('Configs.IsDisabled = 0');
        $result = $this->fetchAll($select);

        return $result;
    }

}