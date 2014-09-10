<?php
/**
 * Application Plugin set global authentication
 *
 */
class Admin_Plugin_Authen extends Zend_Controller_Plugin_Abstract
{
	private $_redirect = null;
	private $_auth = null;
	
	public function __construct() {
		$this->_redirect = new Zend_Controller_Action_Helper_Redirector;
		$this->_auth = Zend_Auth::getInstance();
	}
	
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {

    }

    public function dispatchLoopStartup(
        Zend_Controller_Request_Abstract $request)
    {


    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	/**
    	 *  Redirect to Admin page
    	 */
    	$module = $this->_request->getModuleName();
    	$controller = $this->_request->getControllerName();
    	$action = $this->_request->getActionName();
    	
     	if($module == "admin"){ 
    		if (!$this->_auth->hasIdentity()) {
    	
    			//save back link
    			if($action != "login"){
    				if(!isset($_SESSION['sessionBackLink']['link'])){
    					$sessionBackLink = new Zend_Session_Namespace('sessionBackLink');
    					$sessionBackLink->link = $_SERVER["REQUEST_URI"];
    				}
    			}
    	
    			if($controller == 'users' && ($action == 'login' || $action == 'forgot-password')){}
    			else $this->_redirect->gotoUrl('/admin/users/login')->redirectAndExist();
    		}
     	}
    }
    
    public function login($data){
    	$result = $this->authenticate($data);
    	if(Zend_Auth_Result::SUCCESS === $result){
    		$this->_defineConstantToUse();
    		return $result;
    	}
    	else return false;
    }
    
    /**
     * Authenticate values of credentials
     *
     * @param array $credentials ($_POST)
     * @return string|string
     */
    public function authenticate($credentials){
    	$adapter = $this->getAuthAdapter();
    	$adapter->setIdentity($credentials['Email'])
    	->setCredential($credentials['Password']);
    
    	$select = $adapter->getDbSelect();
    	$select->where('IsDisabled = 0');
    
    	/* Authenticate main */
    	$result = $this->_auth->authenticate($adapter);
    
    	if (!$result->isValid()) {
    		return Zend_Auth_Result::FAILURE;
    	}
    
    	// Write auth info to session data
    	$user = $adapter->getResultRowObject();
    	$sessionUser = new Zend_Session_Namespace('User');
    	$sessionUser->Info = $user;
    	$this->_auth->getStorage()->write($user);
    	return Zend_Auth_Result::SUCCESS;
    }
    
    /**
     * @param array $values
     * @return Zend_Auth_Adapter_Interface
     */
    public function getAuthAdapter(){
    	$authAdapter = new Zend_Auth_Adapter_DbTable(
    			Zend_Db_Table_Abstract::getDefaultAdapter());
    
    	$authAdapter->setTableName("Users")
    	->setIdentityColumn("Email")
    	->setCredentialColumn("Password");
    
    	return $authAdapter;
    }
    
    public function logout() {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	Zend_Session::namespaceUnset('user');
    	$this->_redirect->gotoUrl('/admin/users/login')->redirectAndExist();
    }
    
    /**
     * @author tri.van
     * define userId,Email ... to use
     */
    private function _defineConstantToUse() {
    	defined('USER_EMAIL') ? "" : define("USER_EMAIL", $this->_auth->getIdentity()->Email);
    	defined('USER_ID') ? "" : define("USER_ID", $this->_auth->getIdentity()->UserId);
    	defined('USER_FIRST_NAME') ? "" : define("USER_FIRST_NAME", $this->_auth->getIdentity()->FirstName);
    	defined('USER_FULL_NAME') ? "" : define("USER_FULL_NAME", $this->_auth->getIdentity()->FirstName . ' ' . $this->_auth->getIdentity()->LastName);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {

    }

    public function dispatchLoopShutdown()
    {
    }
}
