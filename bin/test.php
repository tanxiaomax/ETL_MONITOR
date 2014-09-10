<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to public directory
defined('PUBLIC_PATH')
|| define('PUBLIC_PATH', realpath(dirname(__FILE__) ));

// Define application environment
define('APPLICATION_ENV', "production");
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
realpath(APPLICATION_PATH . '/forms'),
get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
		APPLICATION_ENV,
		APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();

require_once 'Function/Shell.php';

$test = new Function_Shell();

// $str = '0 6 * * * /home/dsadm/runjob.sh DMMS_ETL pre_step2';

// //stripos($item,$projectname) != FALSE && stripos($item,$jobname) != FALSE
// //&& stripos($item,$scheduletime) != FALSE
// if(stripos($str,'dfdaadf') == FALSE)
// {
// ECHO 1;
// }

$test->DelScheduleTime('dsc-dev-data','DMMS_ETL','pre_step2','dsadm','dsadm123','0 6 * * *');
