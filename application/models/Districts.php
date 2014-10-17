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
	
	public function getDistrictById($districtId){
    	$select = $this->getItemsBySelectQuery(array('DistrictName'), array('DistrictId = '.$districtId, 'IsDisabled = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return null;
    }
}