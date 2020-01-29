<?php

// constant to allow additional includes, debug fuctions, whatever only on dev environments...
define ('DEVMODE', false);

// enable disabling of UI during maintenance
define ('MAINTENANCE', false);

//DB Configuartions
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');
define('DB_PORT', 3306);
// Application Root
define('APPROOT', dirname(dirname( __FILE__ )));

dirname(dirname( __FILE__ ));

define('URLROOT', 'http://URL PATH/');

define('EMAILS_FOR_ERROR_ALERT', [
    'bryan@getinnotized.com'
]);

