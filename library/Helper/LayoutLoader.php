<?php
/**
 * @author Phuc Duong
 * @desc set layout default module
 * @version 1.0
 * @since 2012-12-20
 */
class Helper_LayoutLoader extends Zend_Layout_Controller_Plugin_Layout {

    /**
     * @author Phuc Duong <phuc.duong@kiss-concept.com>
     * @desc set layout
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        /* $this->getLayout()->setLayoutPath(
                Zend_Controller_Front::getInstance()
                        ->getModuleDirectory($request->getModuleName()) . '/layouts/scripts/'
        );                
        $module = $request->getModuleName();       
        if (isset($module) && $module == "admin") {             
            $this->getLayout()->setLayout($module);
        } else {
            //$this->getLayout()->setLayout('layout');
        } */
    }   
}