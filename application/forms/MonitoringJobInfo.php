<?php
class Application_Form_MonitoringjobInfo extends Zend_Form
{
	public function init()
	{
    	$AllJobs = new Application_Model_DbTable_JobsInfo();
    	$AllJobsInfo = $AllJobs->fetchAll();
    	
    	

    	for($i = 0; $i < $AllJobsInfo->count(); $i++)
    	{
	    	$row = $AllJobsInfo->current();
	    	$array = $row->toArray();
	    	
	    	$hostname[$array['HOSTNAME']] = $array['HOSTNAME'];
	    	
	    	$AllJobsInfo->next();
    	}
    	
    	 
    	
    	$this->addElement('select','hosts',
    	array(
    	'label'        => 'Hosts',
    	'multiOptions' =>  $hostname
    		)
    	);
    	
    	$this->addElement('select','projects',
    			array(
    					'label'        => 'Projects'
    					
    			)
    	);
    	
    	
    	$this->addElement('submit', 'submit', array(
    			'ignore'   => true,
    			'label'    => 'Submit',
    	));
    	
    	
    	
    	
		
	}
}