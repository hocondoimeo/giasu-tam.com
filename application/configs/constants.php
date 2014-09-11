<?php
if(APPLICATION_ENV == 'production'){
    define('STATIC_SERVER', '');
}else{
    define('STATIC_SERVER', '');
}

define('NUMBER_OF_ITEM_PER_PAGE', 20);

/************************* Domain *******************************/
if (APPLICATION_ENV == 'local') {
	define('FRONTEND_DOMAIN_NAME', 'http://local.mobile-4impact');
	define('BACKEND_DOMAIN_NAME', 'http://local.mobile-4impact/admin');

} elseif (APPLICATION_ENV == 'dev') {
	define('FRONTEND_DOMAIN_NAME', 'http://mobile-site-dev.4impact.net.au');
	define('BACKEND_DOMAIN_NAME', 'http://mobile-site-dev.4impact.net.au/admin');

}elseif (APPLICATION_ENV == 'test') {
	define('FRONTEND_DOMAIN_NAME', 'http://mobile-site-test.4impact.net.au');
	define('BACKEND_DOMAIN_NAME', 'http://mobile-site-test.4impact.net.au/admin');

} elseif (APPLICATION_ENV == 'showcase') {
	define('FRONTEND_DOMAIN_NAME', 'http://mobile-site-showcase.4impact.net.au');
	define('BACKEND_DOMAIN_NAME', 'http://mobile-site-showcase.4impact.net.au/admin');

} else {
	define('FRONTEND_DOMAIN_NAME', 'http://mobile-site-showcase.4impact.net.au');
	define('BACKEND_DOMAIN_NAME', 'http://mobile-site-showcase.4impact.net.au/admin');
}

/************************* Date Format *******************************/
define('DATE_FORMAT_PHP_MIXED_FULL', 'Y M d H:i:s');
define('DATE_FORMAT_ZEND', 'dd-MM-yyyy');
define('DATE_FORMAT_DATABASE', 'yyyy-MM-dd HH:mm:ss');
define('DATE_FORMAT_TO_MINUTE', 'yyyy-MM-dd HH:mm');
define('DATE_FORMAT_FULL', 'EEE dd MMM yyyy, hh:mm a');
define('DATE_FORMAT_NORMAL', 'MMM dd, yyyy');
define('DATE_FORMAT', 'EEE dd MMM yyyy, ');

define('SESSION_LIFE_TIME_REMEMBER', 7 * 24 * 60 * 60);


/************************* Files *******************************/
define('UPLOAD_PATH_TMP', '/tmp');
define('DATA_PATH', '/home/web/data/' . APPLICATION_ENV);
//define('DATA_PATH', '/var/www');//test local
define('UPLOAD_PATH', DATA_PATH . '/uploads');

define('IMAGE_UPLOAD_PATH', UPLOAD_PATH . '/images/');
define('IMAGE_UPLOAD_PATH_TMP', IMAGE_UPLOAD_PATH .'tmp/');
define('IMAGE_UPLOAD_PATH_BACKUP', IMAGE_UPLOAD_PATH .'backup/');

define('IMAGE_UPLOAD_URI', '/uploads/images/');
define('IMAGE_UPLOAD_URI_TMP', IMAGE_UPLOAD_URI . 'tmp/');

define('IMAGE_UPLOAD_URL', FRONTEND_DOMAIN_NAME.'/uploads/images/');
define('IMAGE_UPLOAD_URL_TMP', IMAGE_UPLOAD_URL .'tmp/');

define('IMAGE_SIZE_LIMIT', 500);
$allowedExt = array('jpeg', 'jpg', 'png', 'gif');
define('IMAGE_ALLOWED_EXT', serialize($allowedExt));