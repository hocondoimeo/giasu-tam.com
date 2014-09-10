<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_UsersController extends Zend_Controller_Action {

    private $_modelUsers = null;

    /**
     * Function init
     */
    public function init() {
        $this->_modelUsers = new Admin_Model_Core_Users();
        $ajaxContext = $this->_helper->getHelper("AjaxContext");
        //$ajaxContext->addActionContext("ajax-get-feeds", "html");
        $ajaxContext->addActionContext("check-validate-email", "json");
        $ajaxContext->initContext();
    }

    /**
     * check validate email with zend validate
     * @author tri.van
     * @param $email
     * @return boolean(true|false)
     * @since Tue Now 3, 9:48 AM
     */
    private function _checkEmail($email){
        $validator = new Zend_Validate_EmailAddress();
        if ($validator->isValid($email)) {
            // email appears to be valid
            return true;
        }
        else {
        // email is invalid; print the reasons
            return false;
        }
    }

     /**
     * check validate email action
     * @author tri.van
     * @param email
     * @return $check
     * @since Tue Now 3, 9:48 AM
     */
    public function checkValidateEmailAction(){
        $email = $this->_getParam("email");
        $check = $this->_checkEmail($email);

        if ($check) $this->view->check = 1;
        else $this->view->check = 0;
    }

     /**
     * index action - show list users
     * @author tri.van
     * @return $user information
     * @since Tue Now 3, 9:48 AM
     */
    public function indexAction() {
         $users = $this->_modelUsers->getAllUsers();
         $this->view->Users = $users;
         $this->view->message = $this->_helper->flashMessenger->getMessages();
    }

     /**
     * view action
     * @author tri.van
     * @param id
     * @return user information
     * @since Tue Now 3, 9:48 AM
     */
    public function viewAction(){
         $userId = $this->_getParam("id",0);
         $user = $this->_modelUsers->find($userId)->current();
         $userUpdate = $this->_modelUsers->find($user->LastUpdatedBy)->current();

         if(!count($user)) {
             $msg = str_replace(array("{subject}","{ID}"),array("User",$userId),ALERT_RECORD_NOT_FOUND);
             $this->_helper->flashMessenger->addMessage($msg);
             $this->_redirect("/admin/users");
         }
         $this->view->User = $user;
         $this->view->userUpdate = $userUpdate;
    }

     /**
     * add action
     * @author tri.van
     * @since Tue Now 3, 9:48 AM
     */
    public function addAction(){

         $userForm = new Admin_Form_Users();
         $userForm->getElement('FirstName')->setRequired(false);
         $userForm->getElement('LastName')->setRequired(false);
         if($this->_request->isPost()){

            $dataPost = $_POST;
            $userForm->getElement('FirstName')->setRequired(true);
            $userForm->getElement('LastName')->setRequired(true);
            if ($userForm->isValid($dataPost)) {

                 $pasword = sha1($_POST['Password']);

                 if(isset($_POST['selectStatus'])) $isDisable = 1;
                 else $isDisable = 0;

                 $data = array('FirstName'=>$_POST['FirstName'],
                 'LastName'=>$_POST['LastName'],
                 'Email'=>strtolower($_POST['Email']),
                 'Password'=>$pasword,
                 'IsDisabled'=>$isDisable,
                 'LastUpdated'=>Zend_Date::now()->toString(DATE_FORMAT_DATABASE));

                 $rs = $this->_modelUsers->add($data);
                 if($rs){
                      $msg = str_replace("{subject}", "User",ALERT_MESSAGES_ADD_SUCCESS);
                      $this->_modelUsers->update(array('LastUpdatedBy'=>USER_ID)," UserId = ".$rs);
                 }
                 else {
                      $errorRealText = '/';
                      $msg = str_replace("{subject}", "User",ALERT_MESSAGES_ADD_ERROR_OTHER.$errorRealText);
                 }

                 $this->_helper->flashMessenger->addMessage($msg);
                 $this->_redirect("/admin/users");
            }
            else{
                   $userForm->Email->addErrorMessage('Email is invalid or already exists');
                   $userForm->FirstName->setAttribs(array("countError"=>"0","onkeypress"=>"return runScript(event)"));
                   $userForm->LastName->setAttribs(array("countError"=>"0","onkeypress"=>"return runScript(event)"));

                   $msg = ALERT_MESSAGES_ADD_ERROR_VALIDATION;
                   foreach ($userForm->getMessages() as $key=>$messageFormError){
                         $msg .= '/'.$key;
                   }
                   $this->view->message = $msg;
            }
         }
         $this->view->userForm = $userForm;
    }

     /**
     * update action
     * @author tri.van
     * @param id
     * @since Tue Now 3, 9:48 AM
     */
    public function updateAction(){

         $userForm = new Admin_Form_Users("edit");
         $userId = $this->_getParam("id",0);
         $user = $this->_modelUsers->find($userId)->current();
         if(!count($user)){
             $msg = 'error/This user is not exists.';
             $this->_helper->flashMessenger->addMessage($msg);
             $this->_redirect("/admin/users");
         }
         $this->view->User = $user;

         if($this->_request->isPost()){
            $dataPost = $_POST;
            $this->view->oldEmail = $_POST["Email"];
            $this->view->oldFirstName = $_POST["FirstName"];
            $this->view->oldLastName = $_POST["LastName"];

            $pasword = $user->Password;
            if($_POST['Password'] == "" && $_POST["ConfirmPassword"] == ""){
                 $userForm->getElement('Password')->setRequired(false);
                 $userForm->getElement('ConfirmPassword')->setRequired(false);
            }
            if(strtolower($user->Email) == strtolower($_POST["Email"])){
                 $userForm->getElement('Email')->clearValidators();
            }

            if ($userForm->isValid($dataPost)) {
                 if($_POST['Password'] != "")
                     $pasword = sha1($_POST['Password']);

                 if(isset($_POST['selectStatus'])) $isDisable = 1;
                 else $isDisable = 0;

                 $data = array('FirstName'=>$_POST['FirstName'],
                 'LastName'=>$_POST['LastName'],
                 'Email'=>strtolower($_POST['Email']),
                 'Password'=>$pasword,
                 'IsDisabled'=>$isDisable,
                 'LastUpdated'=>Zend_Date::now()->toString(DATE_FORMAT_DATABASE),
                 'LastUpdatedBy'=>USER_ID);

                 $this->_modelUsers->update($data,"UserId = ".$userId);
                 $msg = str_replace("{subject}", "User",ALERT_MESSAGES_UPDATE_SUCCESS);
                 $this->_helper->flashMessenger->addMessage($msg);

                 $this->_redirect("/admin/users");
            }
            else{
                 $userForm->Email->addErrorMessage('Email is invalid or already exists');

                 $msg = ALERT_MESSAGES_UPDATE_ERROR_VALIDATION;
                 foreach ($userForm->getMessages() as $key=>$messageFormError){
                      $msg .= '/'.$key;
                 }
                 $this->view->message = $msg;
            }
         }

         $userForm->populate($user->toArray());
         $this->view->userForm = $userForm;
    }

     /**
     * delete action
     * @author tri.van
     * @param id
     * @since Tue Now 3, 9:48 AM
     */
    public function deleteAction(){
         $userId = $this->_getParam("id",0);
         $confirm = $this->_getParam("confirm",0);
         if($confirm){
                 $this->_modelUsers->delete("UserId = ".$userId);
                 $msg = str_replace("{subject}", "User",ALERT_MESSAGES_DELETE_SUCCESS);
                 $this->_helper->flashMessenger->addMessage($msg);

                 $this->_redirect("/admin/users");
         }
         else{
                 $user = $this->_modelUsers->find($userId)->current();
                 if(!count($user)){
                     $msg = str_replace(array("{subject}","{ID}"),array("User",$userId),ALERT_RECORD_NOT_FOUND);
                     $this->_helper->flashMessenger->addMessage($msg);
                     $this->_redirect("/admin/users");
                 }
                 $this->view->User = $user;
         }
    }

     /**
     * login action
     * @author tri.van
     * @since Tue Now 3, 9:48 AM
     */
    public function loginAction(){
        $this->_helper->layout()->setLayout('admin-login');

        //save backlink
        $backLink = null;
        if (isset($_SERVER['HTTP_REFERER'])) {
            if (strstr($backLink, "/login") != "/login"){
                $backLink = $_SERVER['HTTP_REFERER'];
                $this->view->backLink = $backLink;
            }
        }

        //check authentication first
        $zendAuth = Zend_Auth::getInstance();
        if ($zendAuth->hasIdentity()) {
                $this->_redirect("/admin");
        }

        if($this->_request->isPost()){

            if(!empty($_POST["backLink"])) $backLink = $_POST["backLink"];

            $auth = new Admin_Plugin_Authen();
            if(empty($_POST['username']) || empty($_POST['password']))
                $this->view->rs = false;
            else{
                $data = array();
                $data['Email'] = $_POST['username'];
                $data['Password'] = sha1($_POST['password']);
                $rs = $auth->login($data);
                if(!$rs) $this->view->rs = false;
                else {
                        //save LastLogin to database
                        $this->_modelUsers->update(array("LastLogin"=>Zend_Date::now()->toString(DATE_FORMAT_DATABASE)
                        ),"UserId = ".USER_ID);

                        //if choice remember password
                        if(isset($_POST["remember"])) {
                            $saveHandler = Zend_Session::getSaveHandler();
                            $saveHandler->setOverrideLifetime(true);
                            $saveHandler->setLifetime(SESSION_LIFE_TIME_REMEMBER);
                        }

                        //redirect
                        if(empty($backLink)) $this->_redirect("/admin	");
                        else {
                               if(isset($_SESSION['sessionBackLink']['link'])){
                                     $link = $_SESSION['sessionBackLink']['link'];
                                     Zend_Session::namespaceUnset('sessionBackLink');
                                     $this->_redirect($link);
                               }
                               else $this->_redirect($backLink);
                        }
                }
            }
        }
    }

     /**
     * forgot password action
     * @author tri.van
     * @since Tue Now 3, 9:48 AM
     */
    public function forgotPasswordAction(){
        $this->_helper->layout()->setLayout('admin-login');

        //check authentication first
        $zendAuth = Zend_Auth::getInstance();
        if ($zendAuth->hasIdentity()) {
                $this->_redirect("/admin");
        }

        if($this->_request->isPost()){
             if(!empty($_POST["username"])){

                $check = $this->_checkEmail($_POST["username"]);
                if ($check){ // check valiate email first

                     $checkIsset = $this->_modelUsers->checkIssetEmail($_POST["username"]);
                     if($checkIsset){ // check isset in DB
                            //send mail to user

                            $email = $_POST["username"];
                            $mailSubject = null;$mailMessageContent = null;
                            $mailUserName =null;$mailFrom = null;
                            $configMails = null;
                            try{
                                $modelConfig = new Application_Model_Configs();
                                $configMails = $modelConfig->getConfigValueByCategoryCode("GROUP_CONFIG_MAIL");
                                foreach ($configMails as $key=>$configMail){
                                    switch ($configMail["ConfigCode"]){
                                        case "mail-subject": $mailSubject = $configMail["ConfigValue"];break;
                                        case "mail-message-content": $mailMessageContent = $configMail["ConfigValue"];break;
                                        case "mail-user-name": $mailUserName = $configMail["ConfigValue"];break;
                                        case "mail-user-name-from": $mailFrom = $configMail["ConfigValue"];break;
                                    }
                                }
                            }catch (Zend_Exception $e){}

                            $rsInitMail = $this->_initMail($configMails);
                            if($rsInitMail[0]){
                               $subject = $mailSubject;
                               $newPassword = rand(111111, 999988);

                               $message = str_replace(array("{object}","{PASSWORD}"),array("\r\n",$newPassword),$mailMessageContent);
                               $sendResult = $this->sendMail($email, $subject, $message,$mailUserName,$mailFrom);

                               if($sendResult[0]){
                                    $getUser = $this->_modelUsers->getUserFromEmail($email);
                                    $this->_modelUsers->update(array("Password"=>sha1($newPassword)),"UserId = ".$getUser->UserId);
                                    $this->view->rs = true;
                               }
                               else{
                                    $this->view->rsSendMail = $sendResult;
                                    $this->view->rs = false;
                               }
                            }
                            else{
                                    $this->view->rsSendMail = $sendResult;
                                    $this->view->rs = false;
                            }
                     }
                     else $this->view->rs = false;
                }else $this->view->rs = false;
             }
             else $this->view->rsEmpty = false;
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
            $mail = new Zend_Mail();
            $mail->addTo($email);
            $mail->setSubject($subject);
            $mail->setBodyText($message);
            $mail->setFrom($mailUserName,$mailFrom);

            //Send it!
            $mail->send();
            return array(true,"");
        } catch (Exception $e){
            $sent = $e->getMessage();
            return array(false,$sent);
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
                }
            }

            $config = array(
                'auth' => 'login',
                'username' => $mailUserName,
                'password' => $mailPassword,
                'ssl' => $mailSSL,
                'port' => (int)$mailPort
            );

            $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            Zend_Mail::setDefaultTransport($mailTransport);
            return array(true,"");
        } catch (Zend_Exception $e){
            return array(false,$e->getMessage());
        }
    }

     /**
     * log out action
     * @author tri.van
     * @since Tue Now 3, 9:48 AM
     */
    public function logoutAction(){

        $auth = new Admin_Plugin_Authen();
        $auth->logout();
    }
}