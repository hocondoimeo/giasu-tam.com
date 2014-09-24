<?php

/**
 * Model for menus
 * @author quang.nguyen
 */
class Application_Model_Tutors extends Application_Model_Abstract {
    /**
     * Instance table name
     * @var type
     */
    protected $_name = 'Tutors';
    /**
     * Instance primary key in config
     */
    protected $_primary = 'TutorId';

    protected $_fields = array(
        /* 'Menus.IsDisabled'   => 'IsDisabled' */
    );
    protected $_search = array(
       /* 'Menus.MenuName'      => 'config-name',
       'Menus.MenuUrl'       => 'config-code', */
    );
    protected $_sort = array(
      /*  'Menus.MenuName DESC' => 'Default', */
    );

    /**
     * Get all avaiabled
     * @author quang.nguyen
     * @return Zend_Db_Table_Select
     * @since Tue Now 12, 9:48 AM
     */
    public function getAllAvaiabled(){
        return $this->select()->where('IsDisabled = 0')->where('Status = 1')->order("TutorId DESC");
    }

    public function getTutorDetail($tutorId){
    	$cols = array('TutorId', 'UserName', 'Career', 'Email', 'Avatar', 'Birthday', 'Introduction', 'CreatedDate', 'University', 'Address', 'Phone');
    	$select = $this->getItemsBySelectQuery($cols, array('TutorId = '.$tutorId, 'IsDisabled = 0', 'Status = 1'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return null;
    }
}