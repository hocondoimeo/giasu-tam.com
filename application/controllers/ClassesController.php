<?php


/**
 * Controller for Users controller
 *
 * @author  kissconcept
 * @version $Id$
 */
class ClassesController extends Zend_Controller_Action
{
    /**
     * Init model
     */
    public function init() {
        $this->_model = new Application_Model_Classes();
        //$this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    /**
    * Function show all Sites
    */
    public function indexAction() {
        $this->_helper->redirector('show-classes');
    }
    
    public function applyAction(){
    	if(null == $id = $this->_request->getParam('id',null)){
    		$this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
    		$this->_helper->redirector('show-classes');
    	}
    	
    	$form = new Application_Form_Classes();
    	$this->view->form = $form;
    	
    	/* Proccess data post*/
    	if($this->_request->isPost()) {
    		$formData = $this->_request->getPost();
    		if($form->isValid($formData)) {
    			$tutorModel = new Application_Model_Tutors();
    			$data = $_POST;
    			if($tutorModel->checkTutorIsExist($data['TutorId'])){
    				  $tutors = $this->_model->getTutorsOfClass($data['ClassId']);
    				  if(!in_array($data['TutorId'], explode(',' ,$tutors['ClassTutors']))){
    				  		$data['ClassTutors'] = $tutors['ClassTutors'].','.$data['TutorId'];
    				  		if($this->_model->edit($data)){    				  	
	    				  		$modelConfig = new Application_Model_Configs();
		    				  	$detailNews = $modelConfig->getConfigValue('ung-tuyen-gia-su');
		    				  	$this->_redirect('/news/detail/id/'.$detailNews);
    				  		}else{
    				  			$messageStatus ='danger/Hiện tại hệ thống không đáp ứng chức năng này. Mong bạn thông cảm và thử lại.';
    				  			$this->view->messageStatus = $messageStatus;
    				  		}
    				  }else{
	    				  	$messageStatus ='danger/Mã số của bạn đã được úng tuyển lớp học này';
	    				  	$this->view->messageStatus = $messageStatus;
    				  }
    			}else{
    				$messageStatus ='danger/Mã số của bạn không tồn tại';
    				$this->view->messageStatus = $messageStatus;
    			}    				
    		}else{
    				//$this->view->avatar = (isset($_POST['Avatar'])  && !empty($_POST['Avatar']))?$_POST['Avatar']:'';
    				$messageStatus ='danger/Có lỗi xảy ra. Chú ý thông tin những ô sau đây:';
    				$messages      = array();
    				foreach ($form->getMessages() as $fieldName => $message) {
    					$messages[$fieldName] = end($message);
    				}
    				$this->view->messages = $messages;
    				$this->view->messageStatus = $messageStatus;
    		}
    	}
    	
    	$class = $this->_model->getClassDetail($id);
    	$form->populate($class->toArray());
    	$this->view->class = $class;
    	$this->view->id = $id;
    }
    
   /**
    * Function show all Users
    * @return list Users
    * @author 
    */
    public function showClassesAction() {        
        /*Get all data*/
        $paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled());
        $paginator->setItemCountPerPage(CLASSES_ITEMS);
    	$page = $this->_getParam('page',1);
    	$this->view->page = $page;

        /*Assign varible to view*/
        $this->view->paginator = $paginator;
        $this->view->paginator = $paginator->setCurrentPageNumber($page);
        //$this->view->message = $this->_helper->flashMessenger->getMessages();
    }
    
    public function ajaxGetClassesAction(){
    	$this->_helper->layout->disableLayout();
    	$page = $this->_getParam('page',1);
    
    	//set Lastest News pagination
    	$this->view->page = $page;
    	$paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled());
    	$paginator->setItemCountPerPage(CLASSES_ITEMS);
    	$paginator->setCurrentPageNumber($page);
    
    	$this->view->paginator   = $paginator;
    }        
    
    /**
     * init mail
     * @author tri.van
     * @since Tue Now 3, 9:48 AM
     */
    private function _initMail($configMails){
    	try {
    
    		$mailUserName = null;$mailPassword=null;
    		$mailSSL =null;$mailPort = null;
    
    		//get config mail from DB
    		foreach ($configMails as $key=>$configMail){
    			switch ($configMail["ConfigCode"]){
    				case "mail-user-name": $mailUserName = $configMail["ConfigValue"];break;
    				case "mail-password": $mailPassword = $configMail["ConfigValue"];break;
    				case "mail-ssl": $mailSSL = $configMail["ConfigValue"];break;
    				case "mail-port": $mailPort = $configMail["ConfigValue"];break;
    				case "mail-server": $mailServer = $configMail["ConfigValue"];break;
    			}
    		}
    
    		$config = array(
    				'auth' => 'login',
    				'username' => $mailUserName,
    				'password' => $mailPassword,
    				'ssl' => $mailSSL,
    				'port' => (int)$mailPort
    		);
    
    		$mailTransport = new Zend_Mail_Transport_Smtp($mailServer, $config);	
    		Zend_Mail::setDefaultTransport($mailTransport);
    		return array(true,"");
    	} catch (Zend_Exception $e){
    		return array(false,$e->getMessage());
    	}
    }
    
    /**
     * send mail helper
     * @author tri.van
     * @param $email
     * @param $subject
     * @param $message
     * @param $mailUserName
     * @param $mailFrom
     * @since Tue Now 3, 9:48 AM
     */
    private function sendMail($email,$subject,$message,$mailUserName,$mailFrom){
    	try {
    		//Prepare email
    		$mail = new Zend_Mail('UTF-8');
    		//add headers avoid the mail direction to 'spam'/ 'junk' folder
    		$mail->addHeader('MIME-Version', '1.0');
    		$mail->addHeader('Content-Type', 'text/html');
    		$mail->addHeader('Content-Transfer-Encoding', '8bit');
    		$mail->addHeader('X-Mailer:', 'PHP/'.phpversion());
    		
    		$mail->setFrom($mailUserName, $mailFrom);
    		//add reply to avoid the mail direction to 'spam'/ 'junk' folder
    		$mail->setReplyTo($mailFrom, $subject);
    		
    		$mail->addTo($email);
    		$mail->setSubject($subject);
    		$mail->setBodyHtml($message);
    
    		//Send it!
    		$mail->send();
    		return array(true,"");
    	} catch (Exception $e){
    		$sent = $e->getMessage();
    		return array(false,$sent);
    	}
    }   	
   
}