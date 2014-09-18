<?php

class NewsController extends Zend_Controller_Action
{
    protected $_model;

    public function init()
    {
        $this->_model = new Application_Model_News();
    }

    public function indexAction(){
    }

    public function detailAction(){
    	if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-news');
        }
		$this->view->news = $this->_model->getNewsDetail($id);
    }
    
    public function showNewsAction(){
    	if(null == $id = $this->_request->getParam('cate',null)){
    		$this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
    		$this->_helper->redirector('show-news');
    	}
    	$this->view->cate = $id;
    	$query = $this->_model->getNewsByCate($id);
    	
    	$paginator = Zend_Paginator::factory($query);
    	$paginator->setItemCountPerPage(LASTEST_NEWS_ITEMS);
    	$page = $this->_getParam('page',1);
    	$this->view->page = $page;
    	$this->view->paginator = $paginator->setCurrentPageNumber($page);
    }
    
    public function ajaxGetLastestNewsAction(){
    	$this->_helper->layout->disableLayout();
    	$page = $this->_getParam('page',1);
    
    	if(null == $id = $this->_request->getParam('cate',null)){
    		$this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
    		$this->_helper->redirector('show-news');
    	}
    	$this->view->cate = $id;
    	$query = $this->_model->getNewsByCate($id);
    
    	//set Lastest News pagination
    	$this->view->page = $page;
    	$paginator = Zend_Paginator::factory($query);
    	$paginator->setItemCountPerPage(LASTEST_NEWS_ITEMS);
    	$paginator->setCurrentPageNumber($page);
    
    	$this->view->paginator   = $paginator;
    }
}

