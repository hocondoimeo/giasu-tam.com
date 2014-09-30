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
    				
    				if($id = $this->_model->add($data)){
    					$this->_redirect($urlRedirect);
    				}
    			}else{
    				$msgVN = array(
    						"is required and can't be empty" => 'Không được để trống',
    						'is no valid email address in the basic format local-part@hostname' => 'Email không hợp lệ'
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
    	 
        $auth = TBS\Auth::getInstance();

        $providers = $auth->getIdentity();

        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {
            $provider = $this->_getParam('provider');

            switch ($provider) {
                case "facebook":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS\Auth\Adapter\Facebook(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    if($this->_hasParam('error')) {
                        throw new Zend_Controller_Action_Exception('Facebook login failed, response is: ' . 
                            $this->_getParam('error'));
                    }
                    break;
                case "twitter":
                    if ($this->_hasParam('oauth_token')) {
                        $adapter = new TBS\Auth\Adapter\Twitter($_GET);
                        $result = $auth->authenticate($adapter);
                    }
                    break;
                case "google":
                
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS\Auth\Adapter\Google(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    if($this->_hasParam('error')) {
                        throw new Zend_Controller_Action_Exception('Google login failed, response is: ' . 
                            $this->_getParam('error'));
                    }
                    break;

            }
            // What to do when invalid
            if (isset($result) && !$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Zend_Controller_Action_Exception('Login failed');
            } else {
                $this->_redirect('/user/connect');
            }
        } else { // Normal login page
            $this->view->googleAuthUrl = TBS\Auth\Adapter\Google::getAuthorizationUrl();
            $this->view->googleAuthUrlOffline = TBS\Auth\Adapter\Google::getAuthorizationUrl(true);
            $this->view->facebookAuthUrl = TBS\Auth\Adapter\Facebook::getAuthorizationUrl();

            $this->view->twitterAuthUrl = \TBS\Auth\Adapter\Twitter::getAuthorizationUrl();
        }

    }
    public function connectAction()
    {
        $auth = TBS\Auth::getInstance();
        if (!$auth->hasIdentity()) {
            throw new Zend_Controller_Action_Exception('Not logged in!', 404);
        }
        $this->_registerUser($auth->getIdentity());
        $this->view->providers = $auth->getIdentity();
    }

    private function _registerUser($providers = array())
    {
    	foreach ($providers as $provider){
    		switch ($provider->getName()) {
	    		case 'facebook':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('fb_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$this->_insertData('fb_'.$profile['id'], $profile['email'], $profile['last_name'], $profile['first_name'], $profile['username'], $info);
	    			}	
	    		break;
	    		
	    		case 'google':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('gg_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$this->_insertData('gg_'.$profile['id'], $profile['email'], $profile['given_name'], $profile['family_name'], $profile['name'], $info);
	    			}
	    			break;
	    			
	    		case 'twitter':
	    			$profile = $provider->getApi()->getProfile();
	    			if(count($profile) && !$this->_model->checkUserIsExist('tw_'.$profile['id'])){
	    				$info = base64_encode(serialize($profile));
	    				$this->_insertData('tw_'.$profile['id'], '', $profile['name'], '', $profile['screen_name'], $info);
	    			}
	    			break;
    		}
    	}
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
    	$this->_model->add($data);
    }
    
    public function logoutAction()
    {
        \TBS\Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
}
