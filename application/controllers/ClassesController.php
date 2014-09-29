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
    			/* check tutor is exist */
    			if($tutorModel->checkTutorIsExist($data['TutorId'])){
    				  $tutors = $this->_model->getTutorsOfClass($data['ClassId']);
    				  /* check tutor id exist in class */
    				  if(!in_array($data['TutorId'], explode(',' ,$tutors['ClassTutors']))){
    				  		$data['ClassTutors'] = $tutors['ClassTutors'].','.$data['TutorId'];
    				  		if($this->_model->edit($data)){
    				  			$tutor = $tutorModel->getTutorInfo($data['TutorId']);
    				  			$email = $tutor["Email"];
    				  			$mailUserName =null; $mailFrom = null; $configMails = null;
    				  			try{
    				  				$modelConfig = new Application_Model_Configs();
    				  				$configMails = $modelConfig->getConfigValueByCategoryCode("GROUP_CONFIG_MAIL");
    				  				foreach ($configMails as $key=>$configMail){
    				  					switch ($configMail["ConfigCode"]){
    				  						case "mail-user-name": $mailUserName = $configMail["ConfigValue"];break;
    				  						case "mail-user-name-from": $mailFrom = $configMail["ConfigValue"];break;
    				  					}
    				  				}
    				  				$tutorConfig = $modelConfig->getConfigDetail("ung-tuyen-gia-su");
    				  			}catch (Zend_Exception $e){
    				  			
    				  			}
    				  			 
    				  			$rsInitMail = $this->_initMail($configMails);
    				  			if($rsInitMail[0]){
    				  				$subject = $tutorConfig['ConfigName'];
    				  				 
    				  				// initialize template
    				  				$html = new Zend_View();
    				  				$html->setScriptPath(APPLICATION_PATH . '/views/scripts/email_templates/');
    				  				
    				  				$html->assign('name', $tutor["UserName"]);
    				  				$html->assign('tutorId', $data['TutorId']);
    				  				$html->assign('classId', $data['ClassId']);
    				  				
    				  				$message = $html->render('apply-class.phtml');
    				  				
    				  				$sendResult = $this->sendMail($email, $subject, $message,$mailUserName,$mailFrom);
    				  				 
    				  				if($sendResult[0]){
				    				  	$this->_redirect('/news/detail/id/'.$tutorConfig['ConfigValue']);
    				  				}else{
    				  					$this->view->messageStatus = 'danger/Bạn đã ứng tuyển nhưng gửi email cho bạn thất bại.';
    				  				}
    				  			}else{
    				  				$this->view->messageStatus = 'danger/Hiện tại hệ thống không đáp ứng kịp.';
    				  			}
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
    				$msgVN = array(
    					"is required and can't be empty" => 'Không được để trống',
    					"does not appear to be an integer" => 'Phải là chữ số',
    				);
    				$messageStatus ='danger/Có lỗi xảy ra. Chú ý thông tin những ô sau đây:';
    				$messages      = array();
    				foreach ($form->getMessages() as $fieldName => $message) {
    					$message  = end($message);
    					$key = substr(strstr($message," "), 1);
    					if(in_array($key, array_keys($msgVN))) $message = $msgVN[$key];
    					$messages[$fieldName] = $message;
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
    	$subjectModel = new Application_Model_Subjects();
    	$subjects = $subjectModel->fetchAll($subjectModel->getAllAvaiabled());
    	$this->view->subjects = $subjects;
    	
    	$districtModel = new Application_Model_Districts();
    	$districts = $districtModel->fetchAll($districtModel->getAllAvaiabled());
    	$this->view->districts = $districts;
    	
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
    	$subjectModel = new Application_Model_Subjects();
    	$subjects = $subjectModel->fetchAll($subjectModel->getAllAvaiabled());
    	$this->view->subjects = $subjects;
    	
    	$page = $this->_getParam('page',1);
    	$params = array();
    	$class = $this->_getParam('class', null);
    	$district = $this->_getParam('district', null);
    	$subject = $this->_getParam('subject', null);
    	if(!is_null($class)) $params[] = 'ClassMember='.$class;
    	if(!is_null($district)) $params[] =  'DistrictId='.$district;
    	if (!is_null($subject)){ 
    		$sub = "ClassSubjects like '{$subject},%' ";
    		$sub .= "OR ClassSubjects like '%,{$subject},%' ";
    		$sub .= "OR ClassSubjects like '%,{$subject}' ";
    		$params[] = $sub;
    	}
    
    	//set Lastest News pagination
    	$this->view->page = $page;
    	$paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled($params));
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