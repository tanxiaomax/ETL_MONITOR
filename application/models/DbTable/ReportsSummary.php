<?php
class Application_Model_DbTable_ReportsSummary extends Zend_Db_Table_Abstract
{

	protected $_schema = 'DMMSCOG';
	protected $_name = 'DMMSCOG.RP_RPT_SUMMARY';
	protected $_primary = array('DMMS_DATE','REP_EMAIL_ID','METRIC_ID');
	


}