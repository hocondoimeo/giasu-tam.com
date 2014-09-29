<?php


/**
 * Controller for Districts controller
 *
 * @author  kissconcept
 * @version $Id$
 */
class DistrictsController extends Zend_Controller_Action
{
    /**
     * Init model
     */
    public function init() {
        $this->_model = new Application_Model_Districts();
        //$this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    /**
    * Function show all Sites
    */
    public function indexAction() {
        $this->_helper->redirector('show-districts');
    }    
    
   /**
    * Function show all Districts
    * @return list Districts
    * @author 
    */
    public function showDistrictsAction() {
        /*Get parameters filter*/
        $params            = $this->_getAllParams();
        $params['page']    = $this->_getParam('page',1);
        $params['perpage'] = $this->_getParam('perpage',NUMBER_OF_ITEM_PER_PAGE);
        
        /*Get all data*/
        $paginator = Zend_Paginator::factory($this->_model->getQuerySelectAll($params));
        $paginator->setCurrentPageNumber($params['page']);
        $paginator->setItemCountPerPage($params['perpage']);

        /*Assign varible to view*/
        $this->view->paginator = $paginator;
        $this->view->assign($params);
        $this->view->message = $this->_helper->flashMessenger->getMessages();
    }
    
    /**
    * Add record Districts
    * @param array $formData
    * @return
    * @author 
    */
    public function addDistrictsAction() {
        $form = new Application_Form_Core_Districts();
        $form->changeModeToAdd();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->add($formData)){
                    $msg = str_replace(array("{subject}"),array("Districts"),'success/The {subject} has been added successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                $this->_helper->redirector('show-districts');
            }else{
                 $msg ='danger/There are validation error(s) on the form. Please review the following field(s):';
                 foreach ($form->getMessages() as $key=>$messageFormError){
                      $msg .= '/'.$key;
                 }
                 $this->view->message = array($msg);
            }
        }
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-districts';        
    }
    	
    /**
    * Update record Districts.
    * @param array $formData
    * @return
    * @author 
    */
    public function updateDistrictsAction() {
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-districts');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-districts');
        }
    
        $form = new Application_Form_Core_Districts();
        $form->changeModeToUpdate($id);

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->edit($form->getValues())){
                    $msg = str_replace(array("{subject}"),array("Districts"),'success/The {subject} has been updated successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                 	$this->_helper->redirector('show-districts');
            }else{
                 $msg ='danger/There are validation error(s) on the form. Please review the following field(s):';
                 foreach ($form->getMessages() as $key=>$messageFormError){
                      $msg .= '/'.$key;
                 }
                 $this->view->message = array($msg);
           }
        }
            
        $form->populate($row->toArray());
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-districts';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Districts'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Delete record Districts.
    * @param $id
    * @return
    * @author 
    */
    public function deleteDistrictsAction(){
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-districts');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-districts');
        }
       
        $form = new Application_Form_Core_Districts();
        $form->changeModeToDelete($id) ;
        
        foreach($form->getElements() as $element){
        	if($element instanceof Zend_Form_Element_Text ||
                 $element instanceof Zend_Form_Element_Checkbox ||
                 $element instanceof Zend_Form_Element_Select ||
                 $element instanceof Zend_Form_Element_Textarea)
        		$element->setAttrib('disabled', true);
        }

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            //$id = $formData['NewsId'];
            if(isset($id) && !empty($id) && $this->_model->deleteRow($id)) {
                    $msg = str_replace(array("{subject}"),array("Districts"),'success/The {subject} has been deleted successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
            }
                 	 $this->_helper->redirector('show-districts');
        }
         
        $this->view->id = $id;
        $form->populate($row->toArray());
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-districts';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Districts'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Function show all Districts
    * @return list Districts
    * @author 
    */
    public function ajaxShowDistrictsAction() {
        $this->_helper->layout->disableLayout();
        
        /*Get parameters filter*/
        $params            = $this->_getAllParams();
        $params['page']    = $this->_getParam('page',1);
        $params['perpage'] = $this->_getParam('perpage',20);
        
        /*Get all data*/
        $paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled());
        $paginator->setCurrentPageNumber($params['page']);
        $paginator->setItemCountPerPage($params['perpage']);

        /*Assign varible to view*/
        $this->view->paginator = $paginator;
        $this->view->assign($params);
    }
    
   /**
    * Add record Districts
    * @param array $formData
    * @author 
    */
    public function ajaxAddDistrictsAction() {
    
        $this->_helper->layout->disableLayout();
        
        $form = new Application_Form_Core_Districts();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->add($formData)){
                    die('1');
                }
            }
        }
        $form->populate($form->getValues());
        $this->view->form = $form;
    }
    
   /**
    * Update record Districts
    * @param array $formData
    * @author 
    */
    public function ajaxUpdateDistrictsAction() {
    
        $this->_helper->layout->disableLayout();
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            die('0');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            die('0');
        }
    
        $form = new Application_Form_Core_Districts();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $formData['DistrictId'] = $id;
            if($form->isValid($formData)) {
                if($this->_model->edit($form->getValues())){
                    die('1');
                }
            }
        }
        $form->populate($form->getValues());
        $this->view->form = $form;
    }
    
    /**
    * Delete record Districts.
    * @param $id
    * @author 
    */
    public function ajaxDeleteDistrictsAction(){
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            die('0');
        }

        $row = $this->_model->find($id)->current();
        if($row) {
            if($row->delete()){
                die('1');
            }
        }
        die('0');
    }
}