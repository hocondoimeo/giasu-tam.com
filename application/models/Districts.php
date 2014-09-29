<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Districts extends Application_Model_Abstract {


/********************************************************************
* Define table Districts
********************************************************************/
    protected $_name        = 'Districts';
    protected $_primary     = array('DistrictId',);

    protected $_dependentTables = array(

    );

    protected $_referenceMap    = array(

    );

    public function getAllAvaiabled(){
    	return $this->select()->where('IsDisabled = 0')->order("DistrictId ASC");
    }
    
    public function getFormPairs() {
    	$select = $this->getItemsBySelectQuery(array('DistrictId', 'DistrictName'), array('IsDisabled = 0'));
    
    	$result = $this->fetchAll($select)->toArray();
    	$content = array();
    
    	foreach ($result as $val) {
    		$content[$val['DistrictId']] = $val['DistrictName'];
    	}
    
    	return $content;
    }

}