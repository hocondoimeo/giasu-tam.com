<?php


/**
 * Controller for Grades controller
 *
 * @author  kissconcept
 * @version $Id$
 */
class GradesController extends Zend_Controller_Action
{
    /**
     * Init model
     */
    public function init() {
        $this->_model = new Application_Model_Grades();
        //$this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    /**
    * Function show all Sites
    */
    public function indexAction() {
        $this->_helper->redirector('show-grades');
    }    
    
   /**
    * Function show all Grades
    * @return list Grades
    * @author 
    */
    public function showGradesAction() {
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
    * Add record Grades
    * @param array $formData
    * @return
    * @author 
    */
    public function addGradesAction() {
        $form = new Application_Form_Core_Grades();
        $form->changeModeToAdd();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
            	$data = $_POST;
            	if(isset($data['GradeId'])) unset($data['GradeId']);
            	
            	if($this->_model->add($data)){
                    $msg = str_replace(array("{subject}"),array("Grades"),'success/The {subject} has been added successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                $this->_helper->redirector('show-grades');
            }else{
                 $msg ='danger/There are validation error(s) on the form. Please review the following field(s):';
                 foreach ($form->getMessages() as $key=>$messageFormError){
                      $msg .= '/'.$key;
                 }
                 $this->view->message = array($msg);
            }
        }
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-grades';        
    }
    	
    /**
    * Update record Grades.
    * @param array $formData
    * @return
    * @author 
    */
    public function updateGradesAction() {
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-grades');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-grades');
        }
    
        $form = new Application_Form_Core_Grades();
        $form->changeModeToUpdate($id);

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->edit($form->getValues())){
                    $msg = str_replace(array("{subject}"),array("Grades"),'success/The {subject} has been updated successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                 	$this->_helper->redirector('show-grades');
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
        $this->view->showAllUrl = 'show-grades';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Grades'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Delete record Grades.
    * @param $id
    * @return
    * @author 
    */
    public function deleteGradesAction(){
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-grades');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-grades');
        }
       
        $form = new Application_Form_Core_Grades();
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
            $id = $formData['GradeId'];
            if(isset($id) && !empty($id) && $this->_model->deleteRow($id)) {
                    $msg = str_replace(array("{subject}"),array("Grades"),'success/The {subject} has been deleted successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
            }
                 	 $this->_helper->redirector('show-grades');
        }
         
        $this->view->id = $id;
        $form->populate($row->toArray());
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-grades';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Grades'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Function show all Grades
    * @return list Grades
    * @author 
    */
    public function ajaxShowGradesAction() {
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
    * Add record Grades
    * @param array $formData
    * @author 
    */
    public function ajaxAddGradesAction() {
    
        $this->_helper->layout->disableLayout();
        
        $form = new Application_Form_Core_Grades();

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
    * Update record Grades
    * @param array $formData
    * @author 
    */
    public function ajaxUpdateGradesAction() {
    
        $this->_helper->layout->disableLayout();
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            die('0');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            die('0');
        }
    
        $form = new Application_Form_Core_Grades();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $formData['GradeId'] = $id;
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
    * Delete record Grades.
    * @param $id
    * @author 
    */
    public function ajaxDeleteGradesAction(){
        
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