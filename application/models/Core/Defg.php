<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Core_Defg extends Base_Db_Table_Abstract {


/********************************************************************
* Define table Defg
********************************************************************/
    protected $_name        = 'Defg';
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
            "id"                           => "Defg.id                        = '{{param}}'",
            "value"                        => "Defg.value                     LIKE '%{{param}}%'",
        );
        $this->searchFields['All'] = implode(" OR ", $this->searchFields);

        $this->sortFields = array(
            "id_Sort"                      => "Defg.id                        {{param}}",
            "value_Sort"                   => "Defg.value                     {{param}}",
        );
    }

/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/


}