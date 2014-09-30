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
    protected $_primary     = array('UserId',);

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
            "UserId"                       => "Users.UserId                   = '{{param}}'",
            "Password"                     => "Users.Password                 LIKE '%{{param}}%'",
            "Email"                        => "Users.Email                    LIKE '%{{param}}%'",
            "LastName"                     => "Users.LastName                 LIKE '%{{param}}%'",
            "FirstName"                    => "Users.FirstName                LIKE '%{{param}}%'",
            "UserName"                     => "Users.UserName                 LIKE '%{{param}}%'",
            "IsDisabled"                   => "Users.IsDisabled               = '{{param}}'",
            "LastLogin"                    => "Users.LastLogin                = '{{param}}'",
        );
        $this->searchFields['All'] = implode(" OR ", $this->searchFields);

        $this->sortFields = array(
            "UserId_Sort"                  => "Users.UserId                   {{param}}",
            "Password_Sort"                => "Users.Password                 {{param}}",
            "Email_Sort"                   => "Users.Email                    {{param}}",
            "LastName_Sort"                => "Users.LastName                 {{param}}",
            "FirstName_Sort"               => "Users.FirstName                {{param}}",
            "UserName_Sort"                => "Users.UserName                 {{param}}",
            "IsDisabled_Sort"              => "Users.IsDisabled               {{param}}",
            "LastLogin_Sort"               => "Users.LastLogin                {{param}}",
        );
    }

/********************************************************************
* PUT YOUR CODE HERE
********************************************************************/
    public function checkUserIsExist($openId){
    	$select = $this->getItemsBySelectQuery(array('UserId'), array("OpenId = '{$openId}'", 'IsDisabled = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return true;
    	else return false;
    }

}