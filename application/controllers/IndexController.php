<?php
/**
 * Controller Index
 *
 * @author      code generate
 * @package   	KiSS
 * @version     $Id$
 * @todo remove
 */
class IndexController extends Zend_Controller_Action
{
	protected $_model;

    public function init()
    {
    }
	
    /**
     * Home page - Main Panel
     *
     */
    public function indexAction() {
    }


    /**
     * Home page - Main Panel
     *
     */
	public function ajaxGetSocialCountAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
		require_once realpath(APPLICATION_PATH . '/../library/Function/SocialCount.php');
    	$social = new SocialCount($_SERVER['HTTP_REFERER']);						        	
        $social->addNetwork(new Twitter());
        $social->addNetwork(new Facebook());
        $social->addNetwork(new GooglePlus());
        echo $social->toJSON();
    }
}
