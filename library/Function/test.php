<?php
require_once 'Function/Shell.php';

$test = new Function_Shell();
$test->SetScheduleTime('dsc-dev-data','DMMS_ETL','pre_step2','dsadm','dsadm123',"0 6 * * *");
?>