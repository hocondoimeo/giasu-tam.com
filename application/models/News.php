<?php
/**
 * the news model
 * @author duy.ngo
 * @since 17-1-2013
 */
class Application_Model_News extends Application_Model_Abstract{
	/**
	 * Instance table name
	 * @var type
	 */
	protected $_name = 'News';
	/**
	 * Instance primary key in config
	 */
	protected $_primary = 'NewsId';
	
	protected $_fields = array(
			//'Menus.IsDisabled'   => 'IsDisabled'
	);
	protected $_search = array(
			//'Menus.MenuName'      => 'config-name',
			//'Menus.MenuUrl'       => 'config-code',
	);
	protected $_sort = array(
			//'Menus.MenuName DESC' => 'Default',
	);
   
    public function getNewsDetail($newsId){
    	$cols = array('NewsId', 'Title', 'Summary', 'Content', 'ImageUrl', 'CreatedDate');
    	$select = $this->getItemsBySelectQuery($cols, array('NewsId = '.$newsId, 'IsDisabled = 0'));
    	$result = $this->fetchRow($select);
    	if(count($result)) return $result;
    	else return null; 
    }
    
    public function getNewsByCate($cateId){
    	$cols = array('NewsId', 'Title', 'Summary', 'Content', 'ImageUrl', 'CreatedDate');
    	$select = $this->getItemsBySelectQuery($cols, array('NewsCategoryId = '.$cateId, 'IsDisabled = 0'));
    	 return $select;
    }
    
    public function getNewsByPrivate(){
    	$cols = array('NewsId', 'Title', 'Summary', 'Content', 'ImageUrl', 'CreatedDate');
    	$select = $this->getItemsBySelectQuery($cols, array('IsPrivate = 1', 'IsDisabled = 0'), array('LastUpdated DESC'));
    	return $select;
    }
}