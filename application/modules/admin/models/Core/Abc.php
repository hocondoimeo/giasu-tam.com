<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Core_Abc extends Base_Db_Table_Abstract {


/********************************************************************
* Define table Abc
********************************************************************/
    protected $_name        = 'Abc';
    protected $_primary     = array('id',);

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
            "id"                           => "Abc.id                         = '{{param}}'",
            "value"                        => "Abc.value                      = '{{param}}'",
        );
        $this->searchFields['All'] = implode(" OR ", $this->searchFields);

        $this->sortFields = array(
            "id_Sort"                      => "Abc.id                         {{param}}",
            "value_Sort"                   => "Abc.value                      {{param}}",
        );
    }

/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/


}