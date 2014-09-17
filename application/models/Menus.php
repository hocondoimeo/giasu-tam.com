<?php

/**
 * Model for menus
 * @author quang.nguyen
 */
class Application_Model_Menus extends Application_Model_Abstract {
    /**
     * Instance table name
     * @var type
     */
    protected $_name = 'Menus';
    /**
     * Instance primary key in config
     */
    protected $_primary = 'MenuId';

    protected $_fields = array(
        'Menus.IsDisabled'   => 'IsDisabled'
    );
    protected $_search = array(
       'Menus.MenuName'      => 'config-name',
       'Menus.MenuUrl'       => 'config-code',
    );
    protected $_sort = array(
       'Menus.MenuName DESC' => 'Default',
    );

    /**
     * Get all avaiabled
     * @author quang.nguyen
     * @return Zend_Db_Table_Select
     * @since Tue Now 12, 9:48 AM
     */
    public function getAllAvaiabled(){
        return $this->select()->where('IsDisabled = 0')->order("Position ASC");
    }

    /**
     * Group menu by parent
     * @author quang.nguyen
     * @param Zend_Db_Table_Rowset_Abstract $menus
     * @return array
     * @since Tue Now 16, 9:48 AM
     */
    public function groupMenuByParent(Zend_Db_Table_Rowset_Abstract $menus) {
        $data = array();

        foreach ($menus as $menu) {
            if ($menu->ParentMenuId) {
                if (! isset($data[$menu->ParentMenuId])) $data[$menu->ParentMenuId] = array();

                $data[$menu->ParentMenuId]['childs'][] = $menu->toArray();
            } else {
                if (! isset($data[$menu->MenuId])) {
                    $data[$menu->MenuId] = array_merge($menu->toArray(), array('childs' => array()));
                } else {
                    $data[$menu->MenuId] = array_merge($menu->toArray(), array('childs' => $data[$menu->MenuId]['childs']));
                }
            }
        }

        return $data;
    }
    
    public function getMenu($menus, $menuCode){
    	$returnMenu = array();
    	foreach ($menus as $key => $value) {
    		if(isset($menuCode) && in_array($menuCode, $value))
    			$returnMenu[] = $value;
    	}
    	return $returnMenu;
    }
}