<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Grades extends Application_Model_Abstract {


/********************************************************************
* Define table Grades
********************************************************************/
    protected $_name        = 'Grades';
    protected $_primary     = array('GradeId',);

    protected $_dependentTables = array(

    );

    protected $_referenceMap    = array(

    );


/********************************************************************
* Define field search, sort
********************************************************************/
    public $searchFields = array();
    public $sortFields   = array();

    public function init() {
        parent::init();

         /* Define field to search */
        $this->searchFields = array(
            "GradeId"                      => "Grades.GradeId                 = '{{param}}'",
            "GradeName"                    => "Grades.GradeName               LIKE '%{{param}}%'",
            "IsDisabled"                   => "Grades.IsDisabled              = '{{param}}'",
        );
        $this->searchFields['All'] = implode(" OR ", $this->searchFields);

        $this->sortFields = array(
            "GradeId_Sort"                 => "Grades.GradeId                 {{param}}",
            "GradeName_Sort"               => "Grades.GradeName               {{param}}",
            "IsDisabled_Sort"              => "Grades.IsDisabled              {{param}}",
        );
    }

/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/
    public function getAllAvaiabled(){
    	return $this->select()->where('IsDisabled = 0')->order("GradeId ASC");
    }
    
    public function getFormPairs() {
    	$select = $this->getItemsBySelectQuery(array('GradeId', 'GradeName'), array('IsDisabled = 0'));
    
    	$result = $this->fetchAll($select)->toArray();
    	$content = array();
    
    	foreach ($result as $val) {
    		$content[$val['GradeId']] = $val['GradeName'];
    	}
    
    	return $content;
    }

}