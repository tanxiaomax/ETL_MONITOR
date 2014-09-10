<?php
class Application_Model_DbTable_HisJobsInfo extends Zend_Db_Table_Abstract
{
    protected $_name = 'JOBSINFO_HIS';
    protected $_rowClass = 'Application_Model_HisJobInfo';
}