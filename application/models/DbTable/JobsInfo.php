<?php
class Application_Model_DbTable_JobsInfo extends Zend_Db_Table_Abstract
{
    protected $_name = 'ALLJOBSINFO';
    protected $_rowClass = 'Application_Model_JobInfo';
}