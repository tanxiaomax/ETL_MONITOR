
<?php

class Function_Shell
{
    
    public function GetJobInfo($hostname,$username,$password)
    {
        $bash = 'source ~/.bash_profile';
  
   
        $connection = ssh2_connect($hostname, 22);
                
        if (ssh2_auth_password($connection, $username, $password))
        { 
            echo "Authentication Successful!\n";                        
        } 
        else 
        {
            die('Authentication Failed...');
        }     
        $stream = ssh2_exec($connection, $bash.';dsjob -lprojects');
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
                 
        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);
        
        
        $projects = explode("\n",stream_get_contents($stream));
        
        fclose($errorStream);
        fclose($stream);
        $count = 0;
        
        foreach ($projects as $project)
        {
            if($project != 'ANALYZERPROJECT' && $project != 'dstage1' && trim($project) != '' )
            {
            	$stream = ssh2_exec($connection, $bash.';dsjob -ljobs '. $project);
            	$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
            	 
            	stream_set_blocking($errorStream, true);
            	stream_set_blocking($stream, true);
	
            	$jobs = explode("\n",stream_get_contents($stream));
            	
            	fclose($errorStream);
            	fclose($stream);
            	
            	echo 'project :'. $project . "\n";
            	
            	foreach ($jobs as $job)
            	{
            	    $job = trim($job);
            	    if ($job == '')
            	    {
                        continue;
            	    }
            	    
            	    $stream = ssh2_exec($connection, $bash.';dsjob -jobinfo '. $project .' ' . $job);
            	    $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
            	    
            	    stream_set_blocking($errorStream, true);
            	    stream_set_blocking($stream, true);
            	    
            	    $iscompiled = explode("=", stream_get_contents($errorStream))[1];
            	    
            	    if(trim($iscompiled)<>'0')
            	    {
            	    	//if the job wasn't compiled...
            	    	continue;
            	    }
        
             	    
            	    $jobstatus = explode("\n",stream_get_contents($stream));
            	    $i = 0;
            	    
            	    $jobsinfo[$count][] = $project;
            	    $jobsinfo[$count][]=$job;
            	    echo $job."\n";
            	    echo $count."\n";
            	   
            	   
            	    foreach ($jobstatus as $onestatus)
            	    {
            	               	        
            	        $one=substr($onestatus, (strpos($onestatus, ':') + 1),strlen($onestatus));
            	        
            	        if($i == 0)          	        
            	            $jobsinfo[$count][] = trim($one);   
            	        if($i == 8) 
            	            $jobsinfo[$count][] = trim($one);
            	        if($i == 6)
            	            $jobsinfo[$count][] = trim($one);
            	        
            	        $i++;
            	        
            	      
            	    }
            	    
            	    fclose($errorStream);
            	    fclose($stream);
            	    $count++;
            	   
            	    
            	 
            	 
            	}
            	
            	
            
            
            }
            
        } 
        return $jobsinfo;

    }
    
}


?>


