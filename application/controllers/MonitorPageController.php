<?php
require_once 'My/Controller.php';
require_once 'Function/Tools.php';
require_once 'Function/Shell.php';
class MonitorPageController extends My_Controller
{
    public function monitorresultAction()
    {
			$test1 = bindec(1000)."\n";
			$test2 = bindec(1111)."\n";
			
			echo $test1;
			echo $test2;
			
			$testresult = bindec(1000)|bindec(1111);
			echo $testresult."\n";
			
			echo  decbin((int)$test1 | (int)$test2). "abcd";
        
        
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
    
    
    public function detailmonresultAction()
    {
    	
    	$SetScheduleInfo = new Function_Shell();
    	$form = new Application_Form_ScheduleInfo();
    	$request = $this->getRequest();
    	
    	$hostsinfo = new Application_Model_DbTable_Hosts();
    	
    	$host=$this->_request->getParam('host');
    	$project=$this->_request->getParam('project');
    	$job=$this->_request->getParam('job');
    	$scheduletime = $this->_request->getParam('SCHTIME');
    	
    	$HisJobsInfo = new Application_Model_DbTable_HisJobsInfo();
    	
    	$where3 = 'JOBNAME = "'.$job.'"';
    	$where2 = 'PROJECTNAME = "'.$project.'"';
    	$where1 = 'HOSTNAME = "'.$host.'"';
    	
    	$where = $where1 . " AND ". $where2 . " AND " . $where3;
    	$AllJobs = new Application_Model_DbTable_JobsInfo();
    	 
    	$HisJobInfo = $HisJobsInfo->fetchAll(
    			$where,'LASTRUNTIME ASC');
    	
    	$where = 'HOSTNAME = "'.$host.'"';

    	$userinfo = $hostsinfo->fetchRow($where);
    	
    	
    	$this->view->jobrunresult = $HisJobInfo;
    	$this->view->scheduletime = $scheduletime;
    	$this->view->form = $form;
    	
    	
    	if($this->getRequest()->isPost())
    	{
    		if($form->isValid($request->getPost()))
    		{    		 			 	
    		 	$SetScheduleInfo->SetScheduleTime($host,$project,$job, $userinfo->USER, $userinfo->PASSWORD, $scheduletime); 	
    		}
    	}
    	

    }
       
           
}