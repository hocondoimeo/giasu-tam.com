<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Subjects extends Application_Model_Abstract {


/********************************************************************
* Define table Subjects
********************************************************************/
    protected $_name        = 'Subjects';
    protected $_primary     = array('SubjectId',);

    public $searchFields = array();
    public $sortFields   = array();
    
    
/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/
    
    public function getAllAvaiabled(){
    	return $this->select()->where('IsDisabled = 0')->order("SubjectId ASC");
    }
    
    public function getSubjectName($subjectIds = ''){
    	$cols = array('SubjectId', 'SubjectName');
    	$select = $this->getItemsBySelectQuery($cols, array('SubjectId  IN ('.$subjectIds.') AND IsDisabled = 0'));
    	$result = $this->fetchAll($select);
    	if(count($result)) return $result;
    	else return null;
    }

}