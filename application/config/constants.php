<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define('APP_STATIC_URL', 'http://www.test.com/');

define('APP_NAME', 'Temp Admin');
define('ADMIN_EMAIL', 'spdevtesting@gmail.com');
define('ADMIN_EMAIL_PASSWORD', 'sp@fiverr');
define('ADMIN_NAME', 'Temp Admin');
define('ADMIN',2);
define('SUPER_ADMIN',1);
/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

// OZone-play Characters Limit constants
define('TITLE_PATTERN', "^[a-z _A-Z-.]+$");
define('NAME_PATTERN', "^[a-z A-Z]+$");
define('PHONE_PATTERN', "^[-0-9+]+$");
define('USERNAME_PATTERN', "^[0-9a-zA-Z]+$");
define('TITLE_LIMIT_MIN', 3);
define('TITLE_LIMIT_MAX', 35);
define('EMAIL_LIMIT_MAX', 70);
define('DESC_LIMIT_MIN', 10);
define('DESC_LIMIT_MAX', 200);
define('PASSWORD_MIN_LEN', 6);
define('PASSWORD_MAX_LEN', 20);
define('FEE_MIN', 0);
define('FEE_MAX', 1000000001);

define('UPLOAD_PATH', 'assets/uploads/');
define('DEFAULT_SRC', 'assets/images/avatar.jpg');
define('UPLOAD_IMAGE_SIZE', 512); // 1 MB
define('UPLOAD_IMAGE_TYPES', 'jpg|jpeg|png');
define('IMAGE_MIN_HEIGHT', 162);
define('IMAGE_MAX_HEIGHT', 1000);
define('IMAGE_MIN_WIDTH', 150);
define('IMAGE_MAX_WIDTH', 1500);

define('ERROR_MESSAGE', 'errormsg');
define('SUCCESS_MESSAGE', 'successmsg');

define('DATE_FORMATE', 'Y-m-d');
/* * * Redis constants */
define('REDISHOST', "127.0.0.1");
define('REDISPORT', 6379);
define('REDISAUTH', NULL);

/* * ********MUC constants************ */


define("OPHOST", "localhost");
define("OPPORT", 9090);
define("OPSECRET", "!ncubasys786");


/* * ************RABBIT MQ constants*********************** */

define('RMQHOST', 'localhost');
define('RMQPORT', 5672);
define('RMQUSER', 'guest');
define('RMQPASS', 'guest');
define('RMQVHOST', '/');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
