<?php
class UserController extends Zend_Controller_Action
{
	/**
	 * Init model
	 */
	public function init() {
		$this->_model = new Application_Model_Users();
	}
	
	/**
	 * Function show all Sites
	 */
	public function indexAction() {
		$this->_helper->redirector('login');
	}
	
    public function loginAction()
    {
    	$form = new Application_Form_Users();
    	$this->view->form = $form;

    	$modelConfig = new Application_Model_Configs();
    	$tutorConfig = $modelConfig->getConfigDetail("dang-ky-tim-gia-su");
    	$urlRedirect = '/news/detail/id/'.$tutorConfig['ConfigValue'];
    	$this->view->urlRedirect = $urlRedirect;
    	
    	$getMail = false;
    	/* Proccess data post*/
    	if($this->_request->isPost()) {
    		$formData = $this->_request->getPost();
    		if($form->isValid($formData)) {
    				$data = $_POST;
    				$data['Password'] = '';
    				$data['LastName'] = '';
    				$data['FirstName'] = '';
    				$data['UserName'] = 'guest';
    				$data['LastLogin'] = Zend_Date::now()->toString(DATE_FORMAT_DATABASE);
    				try{
    				if($id = $this->_model->add($data)){
    					$this->_sendEmailToUser('', $data['Email'], $urlRedirect);
    					$this->_redirect($urlRedirect);
    				}
					}catch(Exception $e){
						die($e->getMessage());
					}
    			}else{
    				$msgVN = array(
    						"is required and can't be empty" => 'Không được để trống',
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
        // Here the response of the providers are registered
        if(null !== $provider = $this->_request->getParam('provider',null)){
			$auth = Tbs_Auth::getInstance();
            switch ($provider) {
                case "facebook":
                    if (null !== $code = $this->_request->getParam('code',null)) {
                        $adapter = new Tbs_Auth_Adapter_Facebook($code);
                        $result = $auth->authenticate($adapter);
                    }
                    if(null !== $error = $this->_request->getParam('error',null)) {
                        throw new Zend_Controller_Action_Exception('Facebook login failed, response is: ' .$error);
                    }
                        $getMail = true;
                    break;
                case "twitter":
                    if (null !== $oauthToken = $this->_request->getParam('oauth_token',null)) {
                        $adapter = new Tbs_Auth_Adapter_Twitter($_GET);
                        $result = $auth->authenticate($adapter);
                    }
                    break;
                case "google":                
                    if (null !== $code = $this->_request->getParam('code',null)) {
                        $adapter = new Tbs_Auth_Adapter_Google($code);
                        $result = $auth->authenticate($adapter);
                    }
                    if(null !== $error = $this->_request->getParam('error',null)) {
                        throw new Zend_Controller_Action_Exception('Google login failed, response is: ' .$error);
                    }
                    	$getMail = true;
                    break;
            }
            // What to do when invalid
            if (isset($result) && !$result->isValid()) {	
                $auth->clearIdentity($this->_request->getParam('provider'));
                throw new Zend_Controller_Action_Exception('Login failed');
            } else {
            	//$auth = Tbs_Auth::getInstance();
            	/* if (!$auth->hasIdentity()) {
            		throw new Zend_Controller_Action_Exception('Not logged in!', 404);
            	} */
            	$info = $this->_registerUser($auth->getIdentity());
            	//$this->view->providers = $auth->getIdentity();
            	
				//$identity = Zend_Registry::get('MultipleIdentities');
				//if(isset($identity) && !empty($identity)){
					//$data = unserialize($identity->identityContainer);
					//if(count($data)){
						//$info = $this->_registerUser($data);
            			//$info =$this->_registerUser($auth->getIdentity());

						if($getMail){
							$name = ''; $email = '';
							if(count($info)){
								$name = (isset($info[0])) ? $info[0]  : '' ;
								$email = (isset($info[1])) ? $info[1]  : '' ;
							}
							$this->_sendEmailToUser($name, $email, $urlRedirect);
						}
						
						$this->view->msg = 'success';
					//}else
						//$this->view->msg = 'failed';
				//}
            }
        } else { // Normal login page
            $this->view->googleAuthUrl = Tbs_Auth_Adapter_Google::getAuthorizationUrl();
            $this->view->googleAuthUrlOffline = Tbs_Auth_Adapter_Google::getAuthorizationUrl(true);
            $this->view->facebookAuthUrl = Tbs_Auth_Adapter_Facebook::getAuthorizationUrl();
            $this->view->twitterAuthUrl = Tbs_Auth_Adapter_Twitter::getAuthorizationUrl();
        }

    }
    
    private function _sendEmailToUser($name, $email, $urlRedirect){
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
    		$tutorConfig = $modelConfig->getConfigDetail("dang-ky-tim-gia-su");
    	}catch (Zend_Exception $e){
    			
    	}
    	
    	$rsInitMail = $this->_initMail($configMails);
    	if($rsInitMail[0]){
	    		$subject = $tutorConfig['ConfigName'];
	    			
	    		// initialize template
	    		$html = new Zend_View();
	    		$html->setScriptPath(APPLICATION_PATH . '/views/scripts/email_templates/');
	    	
	    		$html->assign('name', $name);
	    	
	    		$message = $html->render('register-user.phtml');
	    	
	    		$sendResult = $this->sendMail($email, $subject, $message,$mailUserName,$mailFrom);
	    			
	    		if($sendResult[0]){
	    			$this->_redirect($urlRedirect);
	    		}else{
	    			$this->view->messageStatus = 'danger/Bạn đã đăng ký xong nhưng gửi email cho bạn thất bại.';
	    		}
    	}else{
    		$this->view->messageStatus = 'danger/Hiện tại hệ thống không đáp ứng kịp.';
    	}
    }

    private function _registerUser($providers = array())
    {
    	$returnInfo = array();
    	foreach ($providers as $provider){
    		switch ($provider->getName()) {
	    		case 'facebook':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('fb_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$returnInfo[] = $profile['first_name'].' '.$profile['last_name'] ;
	    				$returnInfo[] = $profile['email'];
	    				$this->_insertData('fb_'.$profile['id'], $profile['email'], $profile['last_name'], $profile['first_name'], $profile['username'], $info);
	    			}	
	    		break;
	    		
	    		case 'google':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('gg_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$returnInfo[] = $profile['family_name'].' '.$profile['given_name'] ;
	    				$returnInfo[] = $profile['email'];
	    				$this->_insertData('gg_'.$profile['id'], $profile['email'], $profile['given_name'], $profile['family_name'], $profile['name'], $info);
	    			}
	    			break;
	    			
	    		case 'twitter':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('tw_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$returnInfo[] = $profile['screen_name'];
	    				$returnInfo[] = '';
	    				$this->_insertData('tw_'.$profile['id'], '', $profile['name'], '', $profile['screen_name'], $info);
	    			}
	    			break;
    		}
    	}
    	return $returnInfo;
    }
    
    private function _insertData($openId, $email, $lastName, $firstName, $userName, $info){
    	$data = array();
    	$data['OpenId']               = !empty($openId)?$openId:'';
    	$data['Email']                  = !empty($email)?$email:'';
    	//$data['Password']          = !empty($openId)?$openId:'';
    	$data['LastName']         = !empty($lastName)?$lastName:'';
    	$data['FirstName']         = !empty($firstName)?$firstName:'';
    	$data['UserName']        = !empty($userName)?$userName:'';
    	$data['Info']                       = !empty($info)?$info:'';
    	$data['LastLogin']           = Zend_Date::now()->toString(DATE_FORMAT_DATABASE);
		try{
			$this->_model->add($data);
		}catch(Exception $e){
			//die($e->getMessage);
		}
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