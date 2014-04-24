<?php
class Application_Model_DbTable_Hosts extends Zend_Db_Table_Abstract
{
    protected $_name = 'HOSTS';
    protected $_rowClass = 'Application_Model_Host';
    //protected $_primary= 'HOSTNAME';
}