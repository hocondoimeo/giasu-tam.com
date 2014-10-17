<?php 
ini_set('session.use_cookies', 0);
ini_set('session.cache_limiter', '');

//switch php function on
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
//echo phpinfo();die; 

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//constants
require_once  realpath(APPLICATION_PATH . '/configs/constants.php');

//config for layout
require_once  realpath(APPLICATION_PATH . '/../library/Function/layout.php');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
     '/../library',
     realpath(APPLICATION_PATH .  '/../library'), 
     //'/home/gia53c14/public_html/library',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();	
//Zend_Session::start();