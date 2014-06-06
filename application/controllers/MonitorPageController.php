<?php
require_once 'My/Controller.php';
require_once 'Function/Tools.php';
class MonitorPageController extends My_Controller
{
    public function monitorresultAction()
    {

        
        
//         $DBS = new Application_Model_DbTable_DBS();
//         $DBSInfo = $DBS->fetchAll();
       
 
        
//         for($i = 0; $i < $DBSInfo->count(); $i++)
//         {
//             $row = $DBSInfo->current();
//             $array = $row->toArray();
        
//             $options= array('host' => $array['HOSTNAME'],
//             'username' => $array['USERNAME'],
//             'password' => $array['PASSWORD'],
//             'dbname' => $array['DBNAME'],
//             'port' => $array['PORT'],
//             'protocol' => 'TCPIP');
//         	$db = Zend_Db::factory('Db2', $options);
        
//         	$MonitorResults = new Application_Model_DbTable_MonitorResults(array('db' => $db
//         				));
        	
        	
        	
//         	$select = $MonitorResults->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
//         	         ->setIntegrityCheck(false);
        	
//         	$select->where('COMPLETION_TS > ?', '2013-01-27')
//         	->join('DMMSCOG.ETL_JOB_STATUS',
//         	       'A.ETL_JOB_STATUS.COMPLETION_TS = ETL_JOB_STATUS.COMPLETION_TS',
//         	'STATUS');
        	       
         	//$rows = $MonitorResults->fetchAll();
         	
    		$MonitorLists = new Application_Model_DbTable_MonitorJobLists();
        	$MonitorList = $MonitorLists->fetchAll();
        	
        	$resultsarray = array();
        	
    		
        	foreach ($MonitorList as $job)
        	{
    		
	        	$where = 'HOSTNAME = "'.$job->HOSTNAME.'" AND '.
	          	'PROJECTNAME = "'.$job->PROJECTNAME.'" AND '.
	          	'JOBNAME = "'.$job->JOBNAME.'"';
	          	
	        	
	    	
	            $MonitorResults = new Application_Model_DbTable_MonitorResults();
		        $MonitorResult =  $MonitorResults->fetchAll($where);
		        
		       
		        	
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

        	}
         	//$DBNAMES[] = $array['DBNAME'];
        	
      	//$DBSInfo->next();
     
        //}
        
        
        $this->view->monitorresults = $resultsarray;
        //$this->view->dbnames = $DBNAMES;
             
        
    }
    
    
    public function dailyresultAction()
    {
		$DBS = new Application_Model_DbTable_DBs();
		$DBSInfo = $DBS->fetchAll();
		
		$MetricName = array('MDES','MDLH','MDMG','MDSC','MDTC','MDTV','MDVC','MDVT');
		$MetricAvgVal = array();
	
    	 
    	
    	
    	        for($i = 0; $i < $DBSInfo->count(); $i++)
    		    {
    		    		$MetricNum = array();
    		            $row = $DBSInfo->current();
    		            $array = $row->toArray();
    	
    		            $options= array('host' => $array['HOSTNAME'],
    		            'username' => $array['USERNAME'],
    		            'password' => $array['PASSWORD'],
    		            'dbname' => $array['DBNAME'],
    		            'port' => $array['PORT'],
    		            'protocol' => 'TCPIP');
    		        	$db = Zend_Db::factory('Db2', $options);
    	
    		        	$MonitorResults = new Application_Model_DbTable_ReportsSummary(array('db' => $db
    		        				));


    		        	$select = $MonitorResults->select();
    		        
    		     
    		            $select
    		       		->from($MonitorResults,array('DATE(DMMS_DATE) AS DMMS_DATE', 'TRIM(METRIC_ID) AS METRIC_ID','sum(metric_value) as SUMVALUE'),'DMMSCOG')
    		        	->where(' METRIC_ID IN (\'MDLH\',\'MDTV\',\'MDTC\',\'MDES\',\'MDVT\',\'MDSC\',\'MDMG\',\'MDVC\') AND DMMS_DATE BETWEEN CURRENT DATE- 14 DAYS AND CURRENT DATE ')
    		        	->group('DMMS_DATE')
    		        	->group('METRIC_ID')
    		        	->order('DMMS_DATE')
    		        	->order('METRIC_ID');
    		        	
    		
    					//$this->view->monitorresults = $MonitorResults->fetchAll($select);
    		            $REPORTS[] = $MonitorResults->fetchAll($select)->toArray();
    		            $DBNAMES[] = $array['DBNAME'];
    		            
    		            
    		            
    		            for ($j=0;$j<count($MetricName);$j++)
    		            {
    		            	$MetricNum[$MetricName[$j]] = Function_Tools::CountMetricID($MetricName[$j],$REPORTS);
    		            	echo $MetricNum[$MetricName[$j]].'Num';
    		            	
    		            	
    		            	
    		            }
    		            
    		            
    		            $selectValue = $MonitorResults->select();
    		            
    		            $selectValue
    		            ->from($MonitorResults,array( 'TRIM(METRIC_ID) AS METRIC_ID','sum(metric_value) as SUMVALUE'),'DMMSCOG')
    		        	->where(' METRIC_ID IN (\'MDLH\',\'MDTV\',\'MDTC\',\'MDES\',\'MDVT\',\'MDSC\',\'MDMG\',\'MDVC\') AND DMMS_DATE BETWEEN CURRENT DATE- 14 DAYS AND CURRENT DATE ')
    		        	->group('METRIC_ID')
    		        	->order('METRIC_ID');
    		            
    		            $Values[] = $MonitorResults->fetchAll($selectValue)->toArray();
    		            
    		            foreach ($Values as $Value)
    		            {
    		            	foreach ($Value as $item)
    		            	{
    		            		$flag = array_search($item['METRIC_ID'], $MetricNum);
    		            		
    		            		//echo $MetricNum[$item['METRIC_ID']].'aaa';
    		            		if ($MetricNum[$item['METRIC_ID']] == 0)
    		            		{

    		            			$MetricAvgVal[$i][$item['METRIC_ID']] = 'NULL';
    		            				
    		            		}
    		            		else
    		            		{
    		            		
    		            			$MetricAvgVal[$i][$item['METRIC_ID']]=$item['SUMVALUE']/$MetricNum[$item['METRIC_ID']];
    		            		}
    		            		
    		            	
    		            	}
    		            }
						
    		            
    		      
    				    $DBSInfo->next();

    		    
    		    }
    		    
    		    $this->view->monitorresults = $REPORTS;
    		    $this->view->dbnames=$DBNAMES;
    		    $this->view->avgvalues= $MetricAvgVal;
    		    
    		    
    		     
    		    
    		    
    }
       
           
}