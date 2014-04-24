<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to public directory
defined('PUBLIC_PATH')
|| define('PUBLIC_PATH', realpath(dirname(__FILE__) ));

// Define application environment
define('APPLICATION_ENV', "production");
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
realpath(APPLICATION_PATH . '/forms'),
get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
		APPLICATION_ENV,
		APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
require_once APPLICATION_PATH . "/../library/Function/Shell.php";
$JobsInfo = new Function_Shell();
$HostsInfo = new Application_Model_DbTable_Hosts();
$TAllJobsInfo = new Application_Model_DbTable_JobsInfo();


$HostInfo =  $HostsInfo->fetchAll();

echo $HostInfo->count();
        	
if($HostInfo != NULL)
{
    
    for($j=0; $j <$HostInfo->count(); $j++)
    {
        $hostrow = $HostInfo->current();
        $hostarray = $hostrow->toArray();
        //echo $hostarray['HOSTNAME']."\n";
        //echo $hostarray['USER']."\n";
        //echo $hostarray['PASSWORD']."\n";
        
        $Results = $JobsInfo->GetJobInfo($hostarray['IP'],$hostarray['USER'],$hostarray['PASSWORD']);
        foreach ($Results as $rows)
        {
            //echo $Result['project'].' ' .$Result['job'].' ' .$Result['status'].' ' .$Result['lastruntime'].' ' .$Result['interimstatus'];
            if($TAllJobsInfo->find($hostarray['HOSTNAME'],$rows[0],$rows[1])->valid())
            {
                $data = array(
                		'JOBSTATUS'      => $rows[2],
                		'INTERIMSTATUS'      => $rows[3],
                        'LASTRUNTIME'        => $rows[4]
                );
                
               $where = $TAllJobsInfo->getAdapter()->quoteInto('HOSTNAME = ?', $hostarray['HOSTNAME'])
                 . $TAllJobsInfo->getAdapter()->quoteInto('AND PROJECTNAME = ?',$rows[0]) 
                      . $TAllJobsInfo->getAdapter()->quoteInto('AND JOBNAME = ?',$rows[1]) ;
               
               $TAllJobsInfo->update($data, $where);
                
            }        
                     
//             $newrow = new Application_Model_JobInfo(array("table"=>$TAllJobsInfo));
            else 
            {
                $newrow = $TAllJobsInfo -> createRow();
                $newrow -> HOSTNAME = $hostarray['HOSTNAME'];
                $newrow -> PROJECTNAME = $rows[0];
                $newrow -> JOBNAME = $rows[1];
                $newrow -> JOBSTATUS = $rows[2];
                $newrow -> INTERIMSTATUS = $rows[3];
                $newrow -> LASTRUNTIME = $rows[4];
                $newrow -> save();
                
            }
            
        }
        
      
        $HostInfo->next();
     }
}      





