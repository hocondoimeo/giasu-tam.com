<?php
abstract class Admin_Model_Abstract extends Zend_Db_Table_Abstract{

    protected $_fields;
    protected $_search;
    protected $_sort;

    /**
     * @author tri.van
     * @return mixed Zend_Db_Table_Rowset|false
     */
    public function getAll($arrColum=null){

        if(empty($arrColum)) $select = $this->select()->from($this);
        else $select = $this->select()->from($this,$arrColum);

        if(!empty($this->_search) && !empty($this->_fields)
        && !empty($this->_sort))
        $this->createSQLSelect($select);

        return $select;
    }

    /**
     * @author tri.van
     * @param $id
     * @return Zend_Db_Table_Select $select
     * @since Tue Now 13, 11:01 AM
     */
    public function getItemById($id,$arrColum = null){

        if(empty($arrColum)) $select = $this->select()->from($this,$arrColum);
        else $select = $this->select()->from($this);

        $select->where("$this->_primary = ?",$id);
        return $select;
    }

    /**
     * get records by select query
     * @author duy.ngo
     * @return mixed Zend_Db_Table_Rowset|false
     * @since 19-11-2012
     */
    public function getItemsBySelectQuery($fields = array(), $wheres = array(), $orders=array()) {
        $select = $this->select();

        if (isset($fields)) {
            $select = $select->from($this, $fields);
        }

        if (isset($wheres)) {
            foreach ($wheres as $where)
                $select->where($where);
        }

        if (isset($orders)) {
            foreach ($orders as $order)
                $select->order($order);
        }
        return $select;
    }


	/**
     * Add new record
     * @param array $data
     * @author tai.nguyen
     * @author duy.ngo
     * @return mixed The primary key value(s) | Exception
     * @since 19-11-2012
     */
    public function add($data) {
        $primaryKey = $this->_primary;
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey[1];
        }
        if(isset($data[$primaryKey]) && !$data[$primaryKey])
            $data[$primaryKey] = null;
        try {
            return $this->createRow($data)->save();
        } catch (Exception $e) {
            //$e->__toString();
            return false;
        }
    }

	/**
	 * Edit record by id or where conditon
     * @author tai.nguyen
     * @author duy.ngo
     * @param array $data
     * @param mixed $where
     * @return boolean return
     * @return boolean | void
     * @since 19-11-2012
     */
    public function edit($data, $where = NULL, $return = TRUE) {
        //Because have some case have primarykey is array
        //We temp add this block code for fix it.
        //author will change it after
        $primaryKey = $this->_primary;
        if (is_array($primaryKey)) {
            $primaryKey = $primaryKey[1];
        }

        try {
            if(is_null($where)){
                if(is_numeric($data[$primaryKey]))
                    $row = $this->find($data[$primaryKey])->current();
                else return false;
                if(!$row && $return)
                    return false;
                $this->_saveArray($row, $data);
            }else{
                if(is_array($where)){
                    $select = $this->select();
                    foreach ($where as $value) {
                        $select->where($value);
                    }
                    $rows = $this->fetchAll($select);
                    if(!count($rows) && $return)
                        return false;
                    foreach ($rows as $row) {
                        $this->_saveArray($row, $data);
                    }
                }elseif(!empty($where)){
                    $select = $this->select()->where($where);
                    $row = $this->fetchRow($select);
                    if(!$row && $return)
                        return false;
                    $this->_saveArray($row, $data);
                }
            }
            if($return) return true;
        } catch (Exception $e) {
            //throw $e;
            if($return) return false;
        }
    }
    /**
	 * save array from Zend_Db_Table_Row_Abstract object
     * @author duy.ngo
     * @param Zend_Db_Table_Row_Abstract $row
     * @since 19-11-2012
     */
    private function _saveArray($row, $data){
        $row->setFromArray($data);
        $row->save();
    }

	/**
	 * toogle record by IsDisabled column
     * @param int $primaryId
     * @param boolean $isDisabled
     * @author duy.ngo
     * @return boolean
     */
    public function setDisable($primaryId, $isDisabled) {
        $primaryKey = $this->_primary;
        return $this->edit(array(
            $primaryKey => $primaryId,
            'IsDisabled' => $isDisabled
        ));
    }

	/**
	 * toogle record by IsDisabled column
     * @param int $primaryId
     * @param boolean $isDisabled
     * @author duy.ngo
     * @return boolean
     */
    public function toogleByField($primaryId, $isDisabled) {
        $primaryKey = $this->_primary;
        return $this->update(array(
            'IsDisabled' => new Zend_Db_Expr('1 - IsDisabled'),
             $primaryKey => $primaryId
        ));
    }

    /**
     * @author tri.van
     * @param Zend_Db_Table_Select $select
     * @return Zend_Db_Table_Select
     */
    public function createSQLSelect(&$select = null){
        if(!isset($select))
            $select = $this->select();

        $front = Zend_Controller_Front::getInstance();
        $params = $front->getRequest()->getParams();

        $typeSearch = '';
        $keywords = '';
        $order = '';
        $by = '';
        $fields = array();

        foreach ($params as $key=>$val) {
            /**
             * get type search
             */
            if ($key == 'typeSearch')
                $typeSearch = $val;

            /**
             * get key words
             */
            if ($key == 'keyWord')
                $keywords = str_replace("'","",$val);

            /**
             * get field sort
             */
            if ($key == 'order')
                $order = $val;

            /**
             * get sort by
             */
            if ($key == 'by')
                $by = $val;

            /**
             * get field
             */
            if (in_array($key, $this->_fields)) {
                if ($val != '')
                    $fields[$key] = $val;
            }
        }

        $SQL = '';

        /**
         * set sql for search
         * search all field and search for field
         */
        if ($typeSearch != '' && $keywords != '') {
            if ($typeSearch == 'all') {
                foreach ($this->_search as $keySearch => $fieldSearch) {
                    if ($SQL != '')
                        $SQL .= ' OR ';
                    $SQL .= "{$keySearch} LIKE " . $this->getAdapter()->quote('%'.$keywords.'%');
                }
                $select->where($SQL);
            } else {
                $fieldSearch = array_search($typeSearch, $this->_search);
                if ($fieldSearch) {
                    $select->where("{$fieldSearch} LIKE '%?%'", $keywords);
                }
            }
        }

        /**
         * field search
         */
        if (is_array($fields)){

//            if(count($fields)<=0){
//                $field=array_search('IsInvisible', $this->_fields);
//                $select->where("{$field} = 0");
//            }
            foreach ($fields as $keyField => $valField) {
                $field = array_search($keyField, $this->_fields);

                if($field!='IsDisabled' && $valField!='-1')
                $select->where("{$field} = ?", $valField);
            }
        }

        // add by phuc.duong
        if( !array_key_exists('IsDisabled', $params) ){
            $field = array_search('IsDisabled', $this->_fields);
            $select->where("{$field} = ?", 0);
        }
        /**
         * order
         */

        if ($order != '' && $by != '') {
            $order = array_search($order, $this->_sort);
            $select->order("{$order} {$by}");
        } else {
            $order = array_search('Default', $this->_sort);
            $select->order("{$order}");
        }
//        return $select;
    }
}
