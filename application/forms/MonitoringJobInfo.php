<?php
class Application_Form_MonitoringjobInfo extends Zend_Form
{
	public function init()
	{
    	$AllJobs = new Application_Model_DbTable_JobsInfo();
    	$AllJobsInfo = $AllJobs->fetchAll();
    	$this->setName('joblist');
    	
    	

    	for($i = 0; $i < $AllJobsInfo->count(); $i++)
    	{
	    	$row = $AllJobsInfo->current();
	    	$array = $row->toArray();
	    	
	    	$hostname[$array['HOSTNAME']] = $array['HOSTNAME'];
	    	
	    	$AllJobsInfo->next();
    	}
    	
    	 $title = new Zend_Form_Element_Select('title');
    	 
    	 
    	
    	$this->addElement('select','hosts',
    	array(
    	'label'        => 'Hosts',
    	'style'        => 'width:250px;',
    	'onchange'      => "getproject(this.value)",
    	'multiOptions' =>  $hostname
    		)
    	);
    	
    	$this->addElement('select','projects',
    			array(
    					'label'        => 'Projects',
    					'style'        => 'width:250px;',
    					'onclick'      => "getjob(hosts.value, this.value)"
    				
    					
    			)
    	);
    	
    	
    	$this->addElement('select','jobs',
    			array(
    					'label'        => 'jobs',
    					'style'        => 'width:250px;'
    					
    	
    			)
    	);
    	
    	
    	
    	
    	$this->addElement('submit', 'submit', array(
    			'ignore'   => true,
    			'onclick'  => 'getValue()',
    			'label'    => 'submit'
    	));
    	
    	
    	
    	
		
	}
}