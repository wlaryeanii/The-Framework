<?php

//Path for uploads
$uploadpath = APPROOT.'/'.'uploads/';
define('UPLOAD_PATH', $uploadpath);

// Constant to secure "cron" jobs
define('JOBSEC', '$2y$10$VLdXLJRsEFF/lgJ2cQPEguWBLvoGSwpKPL.L3A3phIFyhDaDtr4bW');

define('JSVARS',serialize(array(
	'urlroot' => URLROOT
)));

if(!defined('SITENAME')){
	define('SITENAME','Hello, you should change me');
}

define('COMPANYNAME', 'This is probably not your company name...');

define('EMAILS_FOR_ERROR_ALERT', [
    'bryan@getinnotized.com'
]);

// Default color, if the local constant is not set
if (!defined('MAINBACKGROUND')){
    define('MAINBACKGROUND','#E46F2C');
}

// We need a curl timeout value - this is for PBX right now, but needs to be expanded. TODO!
define('PBX_CURL_TIMEOUT',5000);

define('ROUTE_REQUEST',true);
define ('ROUTE_REQUEST_PATH',[]);