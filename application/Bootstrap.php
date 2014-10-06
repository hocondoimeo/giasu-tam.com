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
    
     protected function _initAutoloader()
    {
    	require_once 'Zend/Loader/Autoloader.php';    	  	
    	    	
    	$loader = Zend_Loader_Autoloader::getInstance();
    	$loader->registerNamespace(array('Tbs', realpath(APPLICATION_PATH .  '/../library/Tbs')));
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
    
    protected function _initAttributeExOpenIDPath() {
    	$autoLoader = Zend_Loader_Autoloader::getInstance();
    
    	$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    			'basePath' => APPLICATION_PATH,
    			'namespace' => 'My_',
    	));
    
    	$resourceLoader->addResourceType('openidextension', 'openid/extension/', 'OpenId_Extension');
    	$resourceLoader->addResourceType('authAdapter', 'auth/adapter', 'Auth_Adapter');
    
    	$autoLoader->pushAutoloader($resourceLoader);
    }
    
    protected function _initAppKeysToRegistry() {    
    	$appkeys = new Zend_Config_Ini(APPLICATION_PATH . '/configs/appkeys.ini');
    	Zend_Registry::set('keys', $appkeys);
    }
    
    /**
     *
     * This puts the application.ini setting in the registry
     */
    protected function _initConfig()
    {//var_dump($this->getOptions());die;
    	Zend_Registry::set('config', $this->getOptions());
    }
    
    /**
     *
     * This function initializes routes so that http://host_name/login
     * and http://host_name/logout is redirected to the user controller.
     *
     * There is also a dynamic route for clean callback urls for the login
     * providers
     */
    protected function _initRoutes()
    {
    	$front = Zend_Controller_Front::getInstance();
    	$router = $front->getRouter();
    
    	$route = new Zend_Controller_Router_Route('login/:provider',
    			array(
    					'controller' => 'user',
    					'action' => 'login'
    			));
    	$router->addRoute('login/:provider', $route);
    
    	$route = new Zend_Controller_Router_Route_Static('login',
    			array(
    					'controller' => 'user',
    					'action' => 'login'
    			));
    	$router->addRoute('login', $route);
    
    	$route = new Zend_Controller_Router_Route_Static('logout',
    			array(
    					'controller' => 'user',
    					'action' => 'logout'
    			));
    	$router->addRoute('logout', $route);
    }
}

