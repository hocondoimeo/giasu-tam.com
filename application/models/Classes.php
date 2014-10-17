<?php

/**
 * Model for menus
 * @author quang.nguyen
 */
class Application_Model_Classes extends Application_Model_Abstract {
    /**
     * Instance table name
     * @var type
     */
    protected $_name = 'Classes';
    /**
     * Instance primary key in config
     */
    protected $_primary = 'ClassId';

    protected $_fields = array(
    );
    protected $_search = array(
    );
    protected $_sort = array(
        'Classes.ClassId' => 'Classes.ClassId DESC', 
    );

    /**
     * Get all avaiabled
     * @author quang.nguyen
     * @return Zend_Db_Table_Select
     * @since Tue Now 12, 9:48 AM
     */
    public function getAllAvaiabled($params = array()){
    	$select = $this->select()->where('IsDisabled = 0');
    	if(count($params))
    		foreach ($params as $param)
    			$select->where($param);
        return$select ->order("ClassId DESC");
    }

    public function getClassDetail($classId){
    	$cols = array('ClassId', 'GradeId', 'ClassSubjects', 'ClassDaysOfWeek', 'ClassTime', 'DistrictId',
    								'ClassRequire', 'ClassTutors', 'ClassCost', 'ClassAddress', 'ClassContact');
    	$select = $this->getItemsBySelectQuery($cols, array('ClassId = '.$classId, 'IsDisabled = 0', 'ClassStatus = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return null;
    }
    
    public function getTutorsOfClass($classId){
    	$select = $this->getItemsBySelectQuery(array('ClassTutors'), array('ClassId = '.$classId, 'IsDisabled = 0', 'ClassStatus = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return null;
    }
}