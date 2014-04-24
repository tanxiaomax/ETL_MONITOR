<?php
class MonitorPageController extends Zend_Controller_Action
{
    public function monitorresultAction()
    {

        
        
        $DBS = new Application_Model_DbTable_DBS();
        $DBSInfo = $DBS->fetchAll();
       
 
        
        for($i = 0; $i < $DBSInfo->count(); $i++)
        {
            $row = $DBSInfo->current();
            $array = $row->toArray();
        
            $options= array('host' => $array['HOSTNAME'],
            'username' => $array['USERNAME'],
            'password' => $array['PASSWORD'],
            'dbname' => $array['DBNAME'],
            'port' => $array['PORT'],
            'protocol' => 'TCPIP');
        	$db = Zend_Db::factory('Db2', $options);
        
        	$MonitorResults = new Application_Model_DbTable_MonitorResults(array('db' => $db
        				));
        	
        	
        	
//         	$select = $MonitorResults->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
//         	         ->setIntegrityCheck(false);
        	
//         	$select->where('COMPLETION_TS > ?', '2013-01-27')
//         	->join('DMMSCOG.ETL_JOB_STATUS',
//         	       'A.ETL_JOB_STATUS.COMPLETION_TS = ETL_JOB_STATUS.COMPLETION_TS',
//         	'STATUS');
        	       
         	$rows = $MonitorResults->fetchAll();
        	
        	$MonitorResult =  $MonitorResults->fetchAll();
        	
            if($MonitorResult != NULL)
            {
                for($j=0; $j <$MonitorResult->count(); $j++)
                {
                    $monitorrow = $MonitorResult->current();
                    $monitorarray = $monitorrow->toArray();
                    $resultsarray[] = $monitorarray;
                    $MonitorResult->next();
                }
            }           
         	$DBNAMES[] = $array['DBNAME'];
        	
        	$DBSInfo->next();
     
        }
        
        $this->view->monitorresults = $rows;
        $this->view->dbnames = $DBNAMES;
        
       
        
        
    }
        
        
       
           
}