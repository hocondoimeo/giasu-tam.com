<?php

/**
 * Model for config
 * @author tri.van
 */
class Admin_Model_Core_Users extends Base_Db_Table_Abstract {
    /**
     * Instance table name
     * @var type
     */
    protected $_name = 'Users';
    /**
     * Instance primary key in config
     */
    protected $_primary = 'UserId';

    /**
     * get all users
     * @author tri.van
     * @since Tue Now 12, 9:48 AM
     */
    public function getAllUsers(){
        $select = $this->select()->from($this);
        return $this->fetchAll($select);
    }

    /**
     * check exist email in database
     * @author tri.van
     * @param $email
     * @return boolean(true|false)
     * @since Tue Now 28, 9:48 AM
     */
    public function checkIssetEmail($email){
        $select = $this->select()->from($this)->where("Email = ?",$email);
        $rs = $this->fetchRow($select);
        if(count($rs)) return true;
        else return false;
    }

    /**
     * get user information from email's user
     * @author tri.van
     * @param $email
     * @return Array
     * @since Tue Now 28, 9:48 AM
     */
    public function getUserFromEmail($email){
        $select = $this->select()->from($this,"UserId")->where("Email = ?",$email);
        return $this->fetchRow($select);
    }
}