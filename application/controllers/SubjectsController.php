<?php


/**
 * Controller for Subjects controller
 *
 * @author  kissconcept
 * @version $Id$
 */
class SubjectsController extends Zend_Controller_Action
{
    /**
     * Init model
     */
    public function init() {
        $this->_model = new Application_Model_Subjects();
        //$this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    /**
    * Function show all Sites
    */
    public function indexAction() {
        $this->_helper->redirector('show-subjects');
    }    
    
   /**
    * Function show all Subjects
    * @return list Subjects
    * @author 
    */
    public function showSubjectsAction() {
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
    * Add record Subjects
    * @param array $formData
    * @return
    * @author 
    */
    public function addSubjectsAction() {
        $form = new Application_Form_Core_Subjects();
        $form->changeModeToAdd();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->add($formData)){
                    $msg = str_replace(array("{subject}"),array("Subjects"),'success/The {subject} has been added successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                $this->_helper->redirector('show-subjects');
            }else{
                 $msg ='danger/There are validation error(s) on the form. Please review the following field(s):';
                 foreach ($form->getMessages() as $key=>$messageFormError){
                      $msg .= '/'.$key;
                 }
                 $this->view->message = array($msg);
            }
        }
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-subjects';        
    }
    	
    /**
    * Update record Subjects.
    * @param array $formData
    * @return
    * @author 
    */
    public function updateSubjectsAction() {
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-subjects');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-subjects');
        }
    
        $form = new Application_Form_Core_Subjects();
        $form->changeModeToUpdate($id);

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
                if($this->_model->edit($form->getValues())){
                    $msg = str_replace(array("{subject}"),array("Subjects"),'success/The {subject} has been updated successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
                }
                 	$this->_helper->redirector('show-subjects');
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
        $this->view->showAllUrl = 'show-subjects';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Subjects'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Delete record Subjects.
    * @param $id
    * @return
    * @author 
    */
    public function deleteSubjectsAction(){
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-subjects');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            $this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
            $this->_helper->redirector('show-subjects');
        }
       
        $form = new Application_Form_Core_Subjects();
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
                    $msg = str_replace(array("{subject}"),array("Subjects"),'success/The {subject} has been deleted successfully.');
                 	$this->_helper->flashMessenger->addMessage($msg);
            }
                 	 $this->_helper->redirector('show-subjects');
        }
         
        $this->view->id = $id;
        $form->populate($row->toArray());
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-subjects';
        $controller = ltrim(preg_replace("/([A-Z])/", "-$1", 'Subjects'), '-');
        $this->_helper->viewRenderer->setRender('add-'.$controller);
    }
    
    /**
    * Function show all Subjects
    * @return list Subjects
    * @author 
    */
    public function ajaxShowSubjectsAction() {
        $this->_helper->layout->disableLayout();
        
        /*Get parameters filter*/
        $params            = $this->_getAllParams();
        $params['page']    = $this->_getParam('page',1);
        $params['perpage'] = $this->_getParam('perpage',20);
        
        /*Get all data*/
        $paginator = Zend_Paginator::factory($this->_model->getQuerySelectAll($params));
        $paginator->setCurrentPageNumber($params['page']);
        $paginator->setItemCountPerPage($params['perpage']);

        /*Assign varible to view*/
        $this->view->paginator = $paginator;
        $this->view->assign($params);
    }
    
    /**
     * Add record Subjects
     * @param array $formData
     * @author
     */
    public function ajaxConvertSubjectsAction() {
    
    	$this->_helper->layout->disableLayout();
    
    	$subjectIds    = $this->_getParam('subjects', null);
    
    	$subjects = $this->_model->getSubjectName($subjectIds);
    	
    	$subjectNames = trim($this->_array2string($subjects->toArray(), explode(',', $subjectIds)), ',');
    	
    	//echo "{'subjects':'{$subjectNames}'}";die;
    	echo Zend_Json::encode(array('subjects' => $subjectNames));exit;
    }
    
    private function _array2string($haystack, $needle){
    	$str="";
    	foreach($haystack as $k=>$i){
    		if(is_array($i)){
    			if(in_array($i['SubjectId'], $needle))
    				$str.= ','.$i['SubjectName'];
    			$str.=$this->_array2string($i, $needle);
    		}
    	}
    	return $str;
    }
    
   /**
    * Add record Subjects
    * @param array $formData
    * @author 
    */
    public function ajaxAddSubjectsAction() {
    
        $this->_helper->layout->disableLayout();
        
        $form = new Application_Form_Core_Subjects();

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
    * Update record Subjects
    * @param array $formData
    * @author 
    */
    public function ajaxUpdateSubjectsAction() {
    
        $this->_helper->layout->disableLayout();
        
        /* Check valid data */
        if(null == $id = $this->_request->getParam('id',null)){
            die('0');
        }

        $row = $this->_model->find($id)->current();
        if(!$row) {
            die('0');
        }
    
        $form = new Application_Form_Core_Subjects();

        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $formData['SubjectId'] = $id;
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
    * Delete record Subjects.
    * @param $id
    * @author 
    */
    public function ajaxDeleteSubjectsAction(){
        
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