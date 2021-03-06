<?php
class Tbs_Auth_Adapter_Twitter implements Zend_Auth_Adapter_Interface
{
    protected $_accessToken;
    protected $_requestToken;
    protected $_params;
    protected $_options;
    protected $_consumer;

    public function __construct($params)
    {
        $this->_setOptions();
        $this->_consumer = new Zend_Oauth_Consumer($this->_options);
		$this->_accessToken = Zend_Registry::set('twitterToken', array());
        $this->_setRequestToken($params);
    }

    public function authenticate()
    {
        $result = array();
        $result['code'] = Zend_Auth_Result::FAILURE;
        $result['identity'] = NULL;
        $result['messages'] = array();

        $data = array('tokens' => array('access_token' => $this->_accessToken));

        $identity = new Tbs_Auth_Identity_Twitter($this->_accessToken, $this->_options);
        $result['code'] = Zend_Auth_Result::SUCCESS;
        $result['identity'] = $identity;

        return new Zend_Auth_Result($result['code'], $result['identity'],
                          $result['messages']);
    }

    public static function getAuthorizationUrl()
    {
        $config = Zend_Registry::get('config');
        $options = is_object($config) ? $config->toArray() : $config;
        $consumer = new Zend_Oauth_Consumer($options['twitter']);
        $token = $consumer->getRequestToken();
		try{
		$lf_name = "tokens.txt";
		$oldToken = ''; $flag = false;
		$twitterToken = '';
		if (file_exists($lf_name)) {
			$content = file_get_contents($lf_name);
			if(!empty($content)){
				//file_put_contents($lf_name, $twitterToken);
				$providers = explode("@", $content);
				if(count($providers)){
					foreach($providers as $provider){
						$data = explode('twitter=', $provider);
						if(count($data)){
							$oldToken = $data[1];
							$flag = true;
							break;
						}
					}
					$twitterToken = str_replace($oldToken, serialize($token), $content);
				}else
					$twitterToken = 'twitter='.serialize($token).'@';
			}else
				$twitterToken = 'twitter='.serialize($token).'@';
		}else{
			$fp = fopen($lf_name ,"w");
			fclose($fp);			
			$twitterToken = 'twitter='.serialize($token).'@';
		}
		file_put_contents($lf_name, $twitterToken);
		
		}catch (Zend_Exception $e){
		//var_dump($e->getMessage());die;
        }
        return $consumer->getRedirectUrl(null, $token);
    }

    protected function _setOptions($options = null)
    {
        $config = Zend_Registry::get('config');
        $options = is_object($config) ? $config->toArray() : $config;
        $this->_options = $options['twitter'];
    }

    protected function _setRequestToken($params)
    {
		try{
		$lf_name = "tokens.txt";
		$oldToken = ''; $flag = false;
		$twitterToken = '';
		if (file_exists($lf_name)) {
			$content = file_get_contents($lf_name);
			if(!empty($content)){
				$providers = explode("@", $content);
				if(count($providers)){
					foreach($providers as $provider){
						$data = explode('twitter=', $provider);
						if(count($data)){
							$oldToken = $data[1];
							$flag = true;
							break;
						}
					}
				}				
			}
		}		
		}catch (Zend_Exception $e){
			//var_dump($e->getMessage());die;
		}
		if(!empty($oldToken))
			$token = unserialize($oldToken);
        $accesstoken = $this->_consumer->getAccessToken($params, $token);
        $this->_accessToken = $accesstoken;
    }
}
