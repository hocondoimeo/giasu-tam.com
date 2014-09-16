<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
	
	/**
	 * @author duy ngo
	 * @desc load plugin
	 * @since 2012-12-20
	 */
	protected function _initPlugins() {	
		$this->bootstrap("frontController");
		$this->frontController->registerPlugin(new Admin_Plugin_Authen());
	}
    
    public function _initLoadViewResource() {
        
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
         $view = $layout->getView();

        $this->_setNavigation($view);
    }

    public function _setNavigation($view){

         $config = new Zend_Config_Ini(APPLICATION_PATH.'/layouts/navigation.ini', 'run');
         $navigation = new Zend_Navigation($config->navigation);
         $view = $view->navigation($navigation);

        
    }
}

