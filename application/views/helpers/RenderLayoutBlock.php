<?php

/**
 * Class view helper load block of controller
 *
 * @version 1
 * @author tri.van
 */
class Zend_View_Helper_RenderLayoutBlock extends Zend_View_Helper_Abstract {
    
    /**
     * Method constructure
     * @param string $name
     * @param Zend_View $view 
     */
    public function renderLayoutBlock($name, $view, $params = array()){
        $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        $view->addScriptPath(APPLICATION_PATH.'/layouts/scripts/');
        $this->_setViewByParams($view, $params);//set view
        return $view->render($name);//render view
    }
    
    /**
     * Set params for view block
     * @param Zend_View $view
     * @param array $params
     * @author Tri Van 
     */
    private function _setViewByParams(&$view, $params){
        if(!is_array($params)){
            return ;
        }
        
        //loop for set view
        foreach($params as $key => $value){
            $view->$key = $value;
        }
    }
}