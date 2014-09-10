<?php
if(APPLICATION_ENV == 'production'){
    define('STATIC_SERVER', '');
}else{
    define('STATIC_SERVER', '');
}

define('NUMBER_OF_ITEM_PER_PAGE', 20);

/************************* Date Format *******************************/
define('DATE_FORMAT_PHP_MIXED_FULL', 'Y M d H:i:s');
define('DATE_FORMAT_ZEND', 'dd-MM-yyyy');
define('DATE_FORMAT_DATABASE', 'yyyy-MM-dd HH:mm:ss');
define('DATE_FORMAT_TO_MINUTE', 'yyyy-MM-dd HH:mm');
define('DATE_FORMAT_FULL', 'EEE dd MMM yyyy, hh:mm a');
define('DATE_FORMAT_NORMAL', 'MMM dd, yyyy');
define('DATE_FORMAT', 'EEE dd MMM yyyy, ');

define('SESSION_LIFE_TIME_REMEMBER', 7 * 24 * 60 * 60);
