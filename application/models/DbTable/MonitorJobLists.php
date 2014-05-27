<?php
class Application_Model_DbTable_MonitorJobLists extends Zend_Db_Table_Abstract
{
	protected $_name = 'MONITORJOBSLIST';
	protected $_rowClass = 'Application_Model_MonitorJobList';
	//protected $_primary= 'HOSTNAME';
}