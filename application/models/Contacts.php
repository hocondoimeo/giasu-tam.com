<?php
/**
 * Model for Application_Model_Crawler_SiteLinks
 *
 * @version $Id$
 */

class Application_Model_Contacts extends Application_Model_Abstract {


/********************************************************************
* Define table Contacts
********************************************************************/
    protected $_name        = 'Contacts';
    protected $_primary     = array('ContactId',);

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
            "ContactId"                    => "Contacts.ContactId             = '{{param}}'",
            "ContactName"                  => "Contacts.ContactName           LIKE '%{{param}}%'",
            "ContactContent"               => "Contacts.ContactContent        LIKE '%{{param}}%'",
            "CreatedDate"                  => "Contacts.CreatedDate           = '{{param}}'",
            "IsDisabled"                   => "Contacts.IsDisabled            = '{{param}}'",
            "UserId"                       => "Contacts.UserId                = '{{param}}'",
            "ContactPhone"                 => "Contacts.ContactPhone          LIKE '%{{param}}%'",
            "ContactTitle"                 => "Contacts.ContactTitle          LIKE '%{{param}}%'",
        );
        $this->searchFields['All'] = implode(" OR ", $this->searchFields);

        $this->sortFields = array(
            "ContactId_Sort"               => "Contacts.ContactId             {{param}}",
            "ContactName_Sort"             => "Contacts.ContactName           {{param}}",
            "ContactContent_Sort"          => "Contacts.ContactContent        {{param}}",
            "CreatedDate_Sort"             => "Contacts.CreatedDate           {{param}}",
            "IsDisabled_Sort"              => "Contacts.IsDisabled            {{param}}",
            "UserId_Sort"                  => "Contacts.UserId                {{param}}",
            "ContactPhone_Sort"            => "Contacts.ContactPhone          {{param}}",
            "ContactTitle_Sort"            => "Contacts.ContactTitle          {{param}}",
        );
    }

/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/


}