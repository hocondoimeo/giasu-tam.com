<?php


/**
 * Controller for Users controller
 *
 * @author  kissconcept
 * @version $Id$
 */
class TutorsController extends Zend_Controller_Action
{
    /**
     * Init model
     */
    public function init() {
        $this->_model = new Application_Model_Tutors();
        //$this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }
    /**
    * Function show all Sites
    */
    public function indexAction() {$fileName = Common_FileUploader_qqUploadedFileXhr::copyImage('', '', '');
        $this->_helper->redirector('show-tutors');
    }
    
    public function detailAction(){
    	if(null == $id = $this->_request->getParam('id',null)){
    		$this->_helper->flashMessenger->addMessage('%%ERROR_URL%%');
    		$this->_helper->redirector('show-tutors');
    	}
    	$this->view->tutor = $this->_model->getTutorDetail($id);
    }
    
   /**
    * Function show all Users
    * @return list Users
    * @author 
    */
    public function showTutorsAction() {        
        /*Get all data*/
        $paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled());
        $paginator->setItemCountPerPage(TUTORS_ITEMS);
    	$page = $this->_getParam('page',1);
    	$this->view->page = $page;

        /*Assign varible to view*/
        $this->view->paginator = $paginator;
        $this->view->paginator = $paginator->setCurrentPageNumber($page);
        //$this->view->message = $this->_helper->flashMessenger->getMessages();
    }
    
    public function ajaxGetTutorsAction(){
    	$this->_helper->layout->disableLayout();
    	$page = $this->_getParam('page',1);
    
    	//set Lastest News pagination
    	$this->view->page = $page;
    	$paginator = Zend_Paginator::factory($this->_model->getAllAvaiabled());
    	$paginator->setItemCountPerPage(TUTORS_ITEMS);
    	$paginator->setCurrentPageNumber($page);
    
    	$this->view->paginator   = $paginator;
    }
    
    /**
    * Add record Users
    * @param array $formData
    * @return
    * @author 
    */
    public function registerAction() {
        $form = new Application_Form_Tutors();
        $form->changeModeToAdd();
        /* Proccess data post*/
        if($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if($form->isValid($formData)) {
            	$data = $_POST;
            	$career = unserialize(TUTOR_CAREERS);
            	$search = array(array_search('Giáo Viên', $career), array_search('Giảng Viên', $career));
            	
            	if(in_array($data['Career'], $search) && empty($data['CareerLocation'])){
            		$messageStatus ='danger/Có lỗi xảy ra. Chú ý thông tin những ô sau đây:';
            		$messages      = array('CareerLocation' => 'Nơi Công Tác không được trống nếu là Giáo/Giảng Viên');
            		$this->view->messages = $messages;
            		$this->view->messageStatus = $messageStatus;
            	}else{            	
		            	//copy new image from 'tmp' to 'images' folder then remove it
		            	$fileName = Common_FileUploader_qqUploadedFileXhr::copyImage($data['Avatar'], IMAGE_UPLOAD_PATH_TMP, IMAGE_UPLOAD_PATH);
		            	//copy exist image from 'images' to 'backup' folder then remove it
		            	//$fileNameBackup = Common_FileUploader_qqUploadedFileXhr::copyImage($data['OldImageName'], IMAGE_UPLOAD_PATH, IMAGE_UPLOAD_PATH_BACKUP);
		                
		                if($id = $this->_model->add($data)){
		                	// check isset in DB
		                	//send mail to user
		                	
		                	$email = $_POST["Email"];
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
		                		$tutorConfig = $modelConfig->getConfigDetail("dang-ky-gia-su");
		                	}catch (Zend_Exception $e){
		                		
		                	}
		                	
		                	$rsInitMail = $this->_initMail($configMails);
		                	if($rsInitMail[0]){
		                		$subject = $tutorConfig['ConfigName'];
		                		
		                		// initialize template
		                		$html = new Zend_View();
		                		$html->setScriptPath(APPLICATION_PATH . '/views/scripts/email_templates/');
		                		
		                		$html->assign('name', $data['UserName']);
		                		$html->assign('tutorId', $id);
		                		
		                		$message = $html->render('register-tutor.phtml');
		                		$sendResult = $this->sendMail($email, $subject, $message,$mailUserName,$mailFrom);
		                	
		                		if($sendResult[0]){
		                			$this->_redirect('/news/detail/id/'.$tutorConfig['ConfigValue']);
		                		}
		                		else{
		                			$this->view->messageStatus = 'danger/Gửi email cho bạn thất bại.';
		                		}
		                	}
		                	else{
		                		$this->view->messageStatus = 'danger/Hiện tại hệ thống không đáp ứng kịp.';
		                	}                 	
		                }               
            	} 
            }else{
            	if(isset($_POST['TeachableInClass'])  && !empty($_POST['TeachableInClass']) && isset($_POST['TeachableInClassText']))
            		$form->changeModeToClass($_POST['TeachableInClass'], $_POST['TeachableInClassText']);
            	
            	if(isset($_POST['TeachableSubjects'])  && !empty($_POST['TeachableSubjects']) && isset($_POST['TeachableSubjectsText']))
            		$form->changeModeToSubjects($_POST['TeachableSubjects'], $_POST['TeachableSubjectsText']);
            	
            	if(isset($_POST['TeachableDistricts'])  && !empty($_POST['TeachableDistricts']) && isset($_POST['TeachableDistrictsText']))
            		$form->changeModeToDistricts($_POST['TeachableDistricts'], $_POST['TeachableDistrictsText']);
            	
            	$this->view->avatar = (isset($_POST['Avatar'])  && !empty($_POST['Avatar']))?$_POST['Avatar']:'';
            	$msgVN = array(
            			"is required and can't be empty" => 'Không được để trống',
            			"does not appear to be an integer" => 'Phải là chữ số',
            			'is no valid email address in the basic format local-part@hostname' => 'Email không hợp lệ',
						"record matching '".$_POST['Email']."' was found" => "'".$_POST['Email']."' đã tồn tại"
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
        $this->view->form = $form;
        $this->view->showAllUrl = 'show-users';
    }
    
    /**
     * Function upload image
     * @return json string
     * @author
     */
    
    public function ajaxUploadAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    
    	if ($this->_request->isPost()) {
    		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
    		$allowedExtensions = unserialize(IMAGE_ALLOWED_EXT);
    		// max file size in bytes
    		$sizeLimit = IMAGE_SIZE_LIMIT * 1024;
    	  
    		$uploader = new Common_FileUploader_qqFileUploader($allowedExtensions, $sizeLimit);
    		$result = $uploader->handleUpload(IMAGE_UPLOAD_PATH_TMP, true);
    		// to pass data through iframe you will need to encode all html tags
    		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    	}else
    		echo '{success:true}';
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