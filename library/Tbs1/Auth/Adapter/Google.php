<?php 
class Tbs_Auth_Adapter_Google implements Zend_Auth_Adapter_Interface
{
   protected $_accessToken;
   protected $_requestToken;
   protected $_options;
 
   public function __construct($requestToken)
   {
      $this->_setOptions();
      $this->_setRequestToken($requestToken);
   }
 
   public function authenticate()
   {
      $result = array();
      $result['code'] = Zend_Auth_Result::FAILURE;
      $result['identity'] = NULL;
      $result['messages'] = array();
 
      if(!array_key_exists('error',$this->_accessToken)) {
         $result['code'] = Zend_Auth_Result::SUCCESS;
         $result['identity'] = new Tbs_Auth_Identity_Google($this->_accessToken);
      }
 
      return new Zend_Auth_Result($result['code'],
                                  $result['identity'],
                                  $result['messages']);
   }
 
   public static function getAuthorizationUrl()
   {
      $config = Zend_Registry::get('config');
      $options = is_object($config) ? $config->toArray() : $config;
      return Tbs_OAuth2_Consumer::getAuthorizationUrl($options['google']);
   }
 
   protected function _setRequestToken($requestToken)
   {
      $this->_options['code'] = $requestToken;
      
      $accesstoken = Tbs_OAuth2_Consumer::getAccessToken($this->_options);

      $accesstoken['timestamp'] = time();
      $this->_accessToken = $accesstoken;
   }
 
   protected function _setOptions($options = null)
   {
      $config = Zend_Registry::get('config');
      $options = is_object($config) ? $config->toArray() : $config;
      $this->_options = $options['google'];
   }
}
