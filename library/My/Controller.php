<?php
class My_Controller extends Zend_Controller_Action {
	public function init(){
		$this->generateRightTopData();
	}
	
	protected function generateRightTopData(){
	    	$MonitorLists = new Application_Model_DbTable_MonitorJobLists();
    	$MonitorList = $MonitorLists->fetchAll();
    	 
    	$JobsStatus = array();
    	
    	$jobcount = 0;
    	
    	/* 0 OK
    	 * 1 Warning
    	 * 2 Failed
    	 * 3 Unknown
    	 */
    	 
    	
    	
    	 
    	
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
    			$JobsStatus[] = $monitorarray;
    			$MonitorResult->next();
    			 
    			}
    		}
    		
    		$jobcount++;
    	
    	}
    	
    	
    	$resultsarray = array(0=>0,1=>0,2=>0,3=>0,4=>$jobcount);
    	
    	foreach ($JobsStatus as $JobStatus)
    	{
    		if (trim($JobStatus['JOBSTATUS']) == trim('RUN OK (1)'))
    		{
    			$resultsarray[0]++;
    		}
    		elseif (trim($JobStatus['JOBSTATUS']) == trim('RUN with WARNINGS (2)'))
    		{
    			$resultsarray[1]++;
    		}
    		elseif (trim($JobStatus['JOBSTATUS']) == trim('RUN FAILED (3)'))
    		{
    			$resultsarray[2]++;
    		}
    		else 
    		{
    			$resultsarray[3]++;
    		}
    	}
    	
  
    	
    	$this->view->overviewresults = $resultsarray;
    	
    	//$this->view->monitorresults = $resultsarray;
    	
    }
		
	
}

?>