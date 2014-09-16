<?php

class NewsController extends Zend_Controller_Action
{
    protected $_model;

    public function init()
    {
        $this->_model = new Application_Model_News();
        $this->_model->getNewsDetail(1);
    }

    public function indexAction(){die('index');
    }

    public function detailAction(){
    	$this->_helper->layout()->setLayout('layout');
    	if(null == $id = $this->_request->getParam('id',null)){die('error');
            //$this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            //$this->_helper->redirector('show-news');
        }die('index1');
        $this->_model->getNewsDetail($id);die;
		$this->view->news = $this->_model->getNewsDetail($id);die;
    }
}

