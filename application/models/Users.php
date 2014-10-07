<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Users extends Application_Model_Abstract {


/********************************************************************
* Define table Users
********************************************************************/
    protected $_name        = 'Users';
    protected $_primary     = 'UserId';

    protected $_dependentTables = array(

    );

    protected $_referenceMap    = array(

    );


/********************************************************************
* Define field search, sort
********************************************************************/
    public $searchFields = array();
    public $sortFields   = array();


/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/
    public function checkUserIsExist($openId){
    	$select = $this->getItemsBySelectQuery(array('UserId'), array("OpenId = '{$openId}'", 'IsDisabled = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return false;
    }

}