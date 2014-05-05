<?php

class ConfigPageController extends Zend_Controller_Action
{
    public function hostinfoAction()
    {
        $request = $this->getRequest();
    	$form    = new Application_Form_HostInfo();
    	
    	
    	if($this->getRequest()->isPost())
    	{
    	    if ($form->isValid($request->getPost())) {
    	        $hostinfo = new Application_Model_DbTable_Hosts();    	        
    	        $newRow = $hostinfo->createRow();
    	        $newRow->HOSTNAME= $form->getValue('hostname');
    	        $newRow->IP= $form->getValue('ip');
    	        $newRow->USER= $form->getValue('username');
    	        $newRow->PASSWORD= $form->getValue('password');
    	        $newRow->save();
    	    }
    	}
   	
    	$this->view->form = $form;
     
    }
    
    public function dbinfoAction()
    {
        //$request = $this->getRequest();
        $form = new Application_Form_DBInfo();
        $request = $this->getRequest();
       
       
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($request->getPost()))
            {
                $DBINFO = new Application_Model_DbTable_DBS();
                $newrow = $DBINFO->createRow();
                $newrow->HOSTNAME = $form->getValue('hosts');
                $newrow->PORT = $form->getValue('port');
                $newrow->USERNAME = $form->getValue('username');
                $newrow->PASSWORD = $form->getValue('password');
                $newrow->DBNAME = $form->getValue('database');
                $newrow->save();
               
                
            }
        }
        $this->view->form = $form;
            
   
    }
    
    public function monitoringjobinfoAction()
    {
    	$form = new Application_Form_MonitoringjobInfo();
    	
    	$request = $this->getRequest();
    	
    	$this->view->form = $form;
    	
    	if($this->getRequest()->isPost())
    	{
    		if($form->isValid($request->getPost()))
    		{
    			$JobList = new Application_Model_DbTable_MonitorJobLists();
    			$newrow = $JobList->createRow();
    			$newrow->HOSTNAME = $form->getValue('hosts');
    			$newrow->PROJECTNAME = $form->getValue('projects');
    			$newrow->JOBNAME = $form->getValue('jobs');
    			
    			
//     			foreach ($this->getRequest()->getParams() as $f)
//     			{
//     				echo $f;
//     			}
    			//echo $form->getValue('hosts');
    			
    			$newrow->save();
    			 
    	
    		}
    	}
    }
    
    
    public function getmonitoringprojectAction()
    {
    	Zend_Layout::getMvcInstance()->disableLayout();
    	
    	$q=$this->_request->getParam('q');
//     	$rows = $table->fetchAll(
//     			'bug_status = "NEW"',
//     			'bug_id ASC',
//     			10,
//     			0
//     	);
    	
    	$where = 'HOSTNAME = "'.$q.'"'; 
    	$AllJobs = new Application_Model_DbTable_JobsInfo();
    	$AllJobsInfo = $AllJobs->fetchAll(
				$where);
    	//array_unique
    	foreach($AllJobsInfo as $jobInfo)
    	{
	    	$projectname[] =  $jobInfo->PROJECTNAME;
    	}
    	
    	$result = array_unique($projectname);
    	$result =Zend_Json::encode($result);
    	echo $result;
    }
    
    
    public function getmonitoringjobAction()
    {
    	Zend_Layout::getMvcInstance()->disableLayout();
    	 
    	$host=$this->_request->getParam('host');
    	$project=$this->_request->getParam('project');
    	
	
    	//     	$rows = $table->fetchAll(
    	//     			'bug_status = "NEW"',
    	//     			'bug_id ASC',
    	//     			10,
    	//     			0
    	//     	);

    	$where2 = 'PROJECTNAME = "'.$project.'"';
    	$where1 = 'HOSTNAME = "'.$host.'"';
    	$where = $where1 . " AND ". $where2;
    	$AllJobs = new Application_Model_DbTable_JobsInfo();
    	$AllJobsInfo = $AllJobs->fetchAll(
    			$where);
    	//array_unique
    	foreach($AllJobsInfo as $jobInfo)
    	{
    		$projectname[] =  $jobInfo->JOBNAME;
    	}
    	 
    	$result = array_unique($projectname);
    	$result =Zend_Json::encode($result);
    	echo $result;
    }
    
}