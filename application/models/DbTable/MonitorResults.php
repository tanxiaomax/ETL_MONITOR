<?php
class Application_Model_DbTable_MonitorResults extends Zend_Db_Table_Abstract
{
    
    //protected $_schema = 'DMMSCOG';
    //protected $_name = 'ETL_JOB_STATUS';
    //protected $_primary = array('JOB','COMPETION_TS','STATUS');
	protected $_name = 'ALLJOBSINFO';
    protected $_rowClass = 'Application_Model_MonitorResult';
    
    

}