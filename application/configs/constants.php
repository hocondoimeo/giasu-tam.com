<?php
if(APPLICATION_ENV == 'production'){
    define('STATIC_SERVER', '');
}else{
    define('STATIC_SERVER', '');
}

define('NUMBER_OF_ITEM_PER_PAGE', 20);

/************************* Domain *******************************/

if (APPLICATION_ENV == 'development') {
	define('FRONTEND_DOMAIN_NAME', 'http://giasu.com');
	define('BACKEND_DOMAIN_NAME', 'http://admin.giasu.com');

} elseif (APPLICATION_ENV == 'dev') {
	define('FRONTEND_DOMAIN_NAME', 'http://giasu-tam.com');
	define('BACKEND_DOMAIN_NAME', 'http://admin.giasu-tam.com');

}elseif (APPLICATION_ENV == 'test') {
	define('FRONTEND_DOMAIN_NAME', 'http://giasu-tam.com');
	define('BACKEND_DOMAIN_NAME', 'http://admin.giasu-tam.com');

} elseif (APPLICATION_ENV == 'showcase') {
	define('FRONTEND_DOMAIN_NAME', 'http://giasu-tam.com');
	define('BACKEND_DOMAIN_NAME', 'http://admin.giasu-tam.com');

} else {
	define('FRONTEND_DOMAIN_NAME', 'http://giasu-tam.com');
	define('BACKEND_DOMAIN_NAME', 'http://admin.giasu-tam.com');
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
define('DATA_PATH', '/home/gia53c14/public_html');
//define('DATA_PATH', '/var/www');//test local
define('UPLOAD_PATH', DATA_PATH . '/uploads');

define('IMAGE_UPLOAD_PATH', UPLOAD_PATH . '/images/');
define('IMAGE_UPLOAD_PATH_TMP', IMAGE_UPLOAD_PATH .'tmp/');
define('IMAGE_UPLOAD_PATH_BACKUP', IMAGE_UPLOAD_PATH .'backup/');

define('IMAGE_UPLOAD_URI', '/uploads/images/');
define('IMAGE_UPLOAD_URI_TMP', IMAGE_UPLOAD_URI . 'tmp/');

define('IMAGE_UPLOAD_URL', FRONTEND_DOMAIN_NAME.'/uploads/images/');
define('IMAGE_UPLOAD_URL_TMP', IMAGE_UPLOAD_URL .'tmp/');

define('IMAGE_SIZE_LIMIT', 200);
$allowedExt = array('jpeg', 'jpg', 'png', 'gif');
define('IMAGE_ALLOWED_EXT', serialize($allowedExt));

/************************* News *******************************/
define('FEATURE_NEWS_DESC_COUNT', 40);
define('LASTEST_NEWS_DESC_COUNT', 20);
define('LASTEST_NEWS_COUNT', 10);
define('LASTEST_NEWS_ITEMS', 5);

/************************* Tutors *******************************/
$levels = array('- Chọn trình độ -', 'Cao Đẳng', 'Đại Học', 'Thạc Sỹ', 'Bằng cấp khác');
define('TUTOR_LEVELS', serialize($levels));

$exYears = array('Chưa có', 'Dưới 1 năm', '1 năm', '2 năm', '3 năm', '4 năm', '5 năm', '5-10 năm', '10-20 năm', 'trên 20');
define('EXPERIENCE_YEAR', serialize($exYears));

$careers = array('Sinh Viên Năm 1', 'Sinh Viên Năm 2', 'Sinh Viên Năm 3', 'Sinh Viên Năm 4', 'Đã Tốt Nghiệp', 'Giáo Viên', 'Giảng Viên');
define('TUTOR_CAREERS' , serialize($careers));

define('TUTORS_ITEMS', 5);


/************************* Classes *******************************/
define('CLASSES_ITEMS', 6);
$grades = array('Mầm Non','Lớp 1','Lớp 2','Lớp 3','Lớp 4','Lớp 5','Lớp 6','Lớp 7','Lớp 8','Lớp 9','Lớp 10','Lớp 11','Lớp 12','Ôn Thi Tốt Nghiệp','Luyện Thi ĐH');
define('CLASSES_GRADE' , serialize($grades));