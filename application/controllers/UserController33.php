<?php

class UserController222 extends Zend_Controller_Action {

    /**
     * Application keys from appkeys.ini
     * 
     * @var Zend_Config 
     */
    protected $_keys;

    public function init() {
        $this->_keys = Zend_Registry::get('keys');
    }

    public function indexAction() {
        $this->_helper->redirector('login');
    }

    public function loginAction() {
    	
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

        // get an instace of Zend_Auth
        $auth = Zend_Auth::getInstance();

        // check if a user is already logged
        if ($auth->hasIdentity()) {
            $this->_helper->FlashMessenger('It seems you are already logged into the system ');
            return $this->_redirect('/user/connect');
        }

        // if the user is not logged, the do the logging
        // $openid_identifier will be set when users 'clicks' on the account provider
        $openid_identifier = $this->getRequest()->getParam('openid_identifier', null);
        //die($openid_identifier);

        // $openid_mode will be set after first query to the openid provider
        $openid_mode = $this->getRequest()->getParam('openid_mode', null);

        // this one will be set by facebook connect
        $code = $this->getRequest()->getParam('code', null);

        // while this one will be set by twitter
        $oauth_token = $this->getRequest()->getParam('oauth_token', null);

        $ext = null;

        // do the first query to an authentication provider
        if ($openid_identifier) {
        	switch ($openid_identifier) {
        		case 'twitter':
        			$adapter = $this->_getTwitterAdapter();;
        		break;
        		
        		case 'facebook':
        			$adapter = $this->_getFacebookAdapter();
        		break;
        			
        		case 'google':
        		case 'yahoo':
        			$openid_identifier = ($openid_identifier === 'google')?'https://www.google.com/accounts/o8/id': 'http://me.yahoo.com/';
        			$adapter = $this->_getOpenIdAdapter($openid_identifier);
        			$toFetch = $this->_keys->openid->tofetch->toArray();
        			$ext = $this->_getOpenIdExt('ax', $toFetch);
        			$adapter->setExtensions($ext);
        		break;
        		
        		default:
        			$adapter = $this->_getOpenIdAdapter($openid_identifier);
        			$toFetch = $this->_keys->openid->tofetch->toArray();
        			$ext = $this->_getOpenIdExt('sreg', $toFetch);
        			$adapter->setExtensions($ext);
        		break;
        	}

            // here a user is redirect to the provider for loging
            $result = $auth->authenticate($adapter);
            var_dump($result);die;
            // the following two lines should never be executed unless the redirection faild.
            $this->_helper->FlashMessenger('Redirection faild');
            return $this->_redirect('/user/connect');
        } else if ($openid_mode || $code || $oauth_token) {
            // this will be exectued after provider redirected the user back to us
			$provider = '';
            if ($code) {
                // for facebook
                $adapter = $this->_getFacebookAdapter();
                $provider = 'facebook';
            } else if ($oauth_token) {
                // for twitter
                $adapter = $this->_getTwitterAdapter()->setQueryData($_GET);
                $provider = 'twitter';
            } else {
                // for openid                
                $adapter = $this->_getOpenIdAdapter(null);

                // specify what to grab from the provider and what extension to use
                // for this purpose
                $ext = null;
                
                $toFetch = $this->_keys->openid->tofetch->toArray();
                
                // for google and yahoo use AtributeExchange Extension
                if (isset($_GET['openid_ns_ext1']) || isset($_GET['openid_ns_ax'])) {
                    $ext = $this->_getOpenIdExt('ax', $toFetch);
                } else if (isset($_GET['openid_ns_sreg'])) {
                    $ext = $this->_getOpenIdExt('sreg', $toFetch);
                }

                if ($ext) {
                    $ext->parseResponse($_GET);
                    $adapter->setExtensions($ext);
                }
            }

            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $toStore = array('identity' => $auth->getIdentity());

                if ($ext) {
                    // for openId
                    $toStore['properties'] = $ext->getProperties();
                } else if ($code) {
                    // for facebook
                    $msgs = $result->getMessages();
                    $toStore['properties'] = (array) $msgs['user'];
                } else if ($oauth_token) {
                    // for twitter
                    $identity = $result->getIdentity();
                    // get user info
                    $twitterUserData = (array) $adapter->verifyCredentials();
                    $toStore = array('identity' => $identity['user_id']);
                    if (isset($twitterUserData['status'])) {
                        $twitterUserData['status'] = (array) $twitterUserData['status'];
                    }
                    $toStore['properties'] = $twitterUserData;
                }

                $auth->getStorage()->write($toStore);

                $this->_helper->FlashMessenger('Successful authentication');
                var_dump($auth->getIdentity());die;
                $this->_registerUser($auth->getIdentity(), $provider);
                return $this->_redirect('/user/connect');
            } else {
                $this->_helper->FlashMessenger('Failed authentication');
                $this->_helper->FlashMessenger($result->getMessages());
                return $this->_redirect('/user/connect');
            }
        }
        $url = FRONTEND_DOMAIN_NAME.'/user/login/openid_identifier/';
        $this->view->googleAuthUrl = $url.'google';
        
        $this->view->facebookAuthUrl = $url.'facebook';
        
        $this->view->yahooAuthUrl = $url.'yahoo';
        
        $this->view->twitterAuthUrl = $url.'twitter';
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_helper->FlashMessenger('You were logged out');
        return $this->_redirect('/user/login');
    }

    /**
     * Get My_Auth_Adapter_Facebook adapter
     *
     * @return My_Auth_Adapter_Facebook
     */
    protected function _getFacebookAdapter() {
        extract($this->_keys->facebook->toArray());
        return new My_Auth_Adapter_Facebook($appid, $secret, $redirecturi, $scope);
    }

    /**
     * Get My_Auth_Adapter_Oauth_Twitter adapter
     *
     * @return My_Auth_Adapter_Oauth_Twitter
     */
    protected function _getTwitterAdapter() {
        extract($this->_keys->twitter->toArray());
        return new My_Auth_Adapter_Oauth_Twitter(array(), $appid, $secret, $redirecturi);
    }

    /**
     * Get Zend_Auth_Adapter_OpenId adapter
     *
     * @param string $openid_identifier
     * @return Zend_Auth_Adapter_OpenId
     */
    protected function _getOpenIdAdapter($openid_identifier = null) {
        $adapter = new My_Auth_Adapter_Google($openid_identifier);
        $dir = APPLICATION_PATH . '/../tmp';

        if (!file_exists($dir)) {
            if (!mkdir($dir)) {
                throw new Zend_Exception("Cannot create $dir to store tmp auth data.");
            }
        }
        $adapter->setStorage(new Zend_OpenId_Consumer_Storage_File($dir));

        return $adapter;
    }

    /**
     * Get Zend_OpenId_Extension. Sreg or Ax. 
     * 
     * @param string $extType Possible values: 'sreg' or 'ax'
     * @param array $propertiesToRequest
     * @return Zend_OpenId_Extension|null
     */
    protected function _getOpenIdExt($extType, array $propertiesToRequest) {

        $ext = null;

        if ('ax' == $extType) {
            $ext = new My_OpenId_Extension_AttributeExchange($propertiesToRequest);
        } elseif ('sreg' == $extType) {
            $ext = new Zend_OpenId_Extension_Sreg($propertiesToRequest);
        }

        return $ext;
    }
	
    public function connectAction()
    {
    	$this->view->message = $this->_helper->flashMessenger->getMessages();
    }
    
    private function _registerUser($profile = array(), $provider = '')
    {
    		switch ($provider) {
    			case 'facebook':
    				$profile = $profile['properties'];
    				if(count($profile) && !$this->_model->checkUserIsExist('fb_'.$profile['id'])){
    					$info = base64_encode(serialize($profile));
    					$this->_insertData('fb_'.$profile['id'], $profile['email'], $profile['last_name'], $profile['first_name'], $profile['username'], $info);
    				}
    			break;
    
    			case 'twitter':
    				$profile = $profile['properties'];
    				if(count($profile) && !$this->_model->checkUserIsExist('tw_'.$profile['id'])){
    					$info = base64_encode(serialize($profile));
    					$this->_insertData('tw_'.$profile['id'], '', $profile['name'], '', $profile['screen_name'], $info);
    				}
    			break;

    			default:
    					$profile = $profile['properties'];
    					if(count($profile) && !$this->_model->checkUserIsExist('gg_'.$profile['id'])){
    						$info = base64_encode(serialize($profile));
    						$this->_insertData('gg_'.$profile['id'], $profile['email'], $profile['given_name'], $profile['family_name'], $profile['name'], $info);
    					}
    			break;
    		}
    	}
}

