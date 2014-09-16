<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    public function _initLoadViewResource() {
    	$layout = $this->bootstrap('layout')->getResource('layout');
    	$layout->setLayout('layout');        
        //$this->bootstrap('view');
        //$view = $this->getResource('view');

        //$this->_setNavigation($view);
    }

    public function _setNavigation($view){

         $config = new Zend_Config_Ini(APPLICATION_PATH.'/layouts/navigation.ini', 'run');
         $navigation = new Zend_Navigation($config->navigation);
         $view = $view->navigation($navigation);

        
    }
    
    public function _initDb() {
    	$resource = $this->getPluginResource('db');
    	$options = $resource->getOptions();
    	$db = new Zend_Db_Adapter_Pdo_Mysql($options["params"]);
    	Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }
    
    private function _initSession(){
    	$this->bootstrap('db');
    	$this->bootstrap('session');
    	Zend_Session::start();
    }
}

