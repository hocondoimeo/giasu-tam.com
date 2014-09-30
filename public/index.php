<?php

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    

//config for layout
//require_once APPLICATION_PATH . '/../library/Function/counter.php';
require_once APPLICATION_PATH . '/../library/Function/layout.php';
require_once APPLICATION_PATH . '/configs/constants.php';


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
     //'/../library',
     //'../library',
    get_include_path(),
)));



/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();