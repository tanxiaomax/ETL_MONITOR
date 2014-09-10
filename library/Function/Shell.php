
<?php
require_once 'Function/Tools.php';
class Function_Shell {
	public function GetJobInfo($hostname, $username, $password) {
		$bash = 'source ~/.bash_profile';
		
		$connection = ssh2_connect ( $hostname, 22 );
		
		if (ssh2_auth_password ( $connection, $username, $password )) {
			echo "Authentication Successful!\n";
		} else {
			die ( 'Authentication Failed...' );
		}
		$stream = ssh2_exec ( $connection, $bash . ';dsjob -lprojects' );
		$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
		
		stream_set_blocking ( $errorStream, true );
		stream_set_blocking ( $stream, true );
		
		$projects = explode ( "\n", stream_get_contents ( $stream ) );
		
		fclose ( $errorStream );
		fclose ( $stream );
		$count = 0;
		
		foreach ( $projects as $project ) {
			if ($project != 'ANALYZERPROJECT' && $project != 'dstage1' && trim ( $project ) != '') {
				$stream = ssh2_exec ( $connection, $bash . ';dsjob -ljobs ' . $project );
				$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
				
				stream_set_blocking ( $errorStream, true );
				stream_set_blocking ( $stream, true );
				
				$jobs = explode ( "\n", stream_get_contents ( $stream ) );
				
				fclose ( $errorStream );
				fclose ( $stream );
				
				echo 'project :' . $project . "\n";
				
				foreach ( $jobs as $job ) {
					$scheduleinfo = '';
					$job = trim ( $job );
					if ($job == '') {
						continue;
					}
					
					// cronjob
					
					$stream = ssh2_exec ( $connection, 'crontab -l | grep ' . $project . '| grep ' . $job );
					$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
					
					stream_set_blocking ( $errorStream, true );
					stream_set_blocking ( $stream, true );
					// store the schedule info
					$temp = stream_get_contents ( $stream );
					if ($temp != '') {
						
						$scheduletimes = explode ( "\n", $temp );
						foreach ( $scheduletimes as $scheduletime ) {
							if ($scheduletime != '') {
								$length = stripos ( $scheduletime, '/opt/' );
								if ($length === FALSE) {
									$length = stripos ( $scheduletime, '/home/' );
								}
								echo Function_Tools::DisplayCrontabTime ( substr ( $temp, 0, $length ) ) . "\n";
								$scheduleinfo = $scheduleinfo . substr ( $temp, 0, $length )  . ";";
							}
						}
					} else {
						$scheduleinfo = 'Unschedule';
					}
					
					fclose ( $errorStream );
					fclose ( $stream );
					
					// dsjob
					$stream = ssh2_exec ( $connection, $bash . ';dsjob -jobinfo ' . $project . ' ' . $job );
					$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
					
					stream_set_blocking ( $errorStream, true );
					stream_set_blocking ( $stream, true );
					
					$iscompiled = explode ( "=", stream_get_contents ( $errorStream ) )[1];
					
					if (trim ( $iscompiled ) != '0') {
						// if the job wasn't compiled...
						continue;
					}
					
					$jobstatus = explode ( "\n", stream_get_contents ( $stream ) );
					// the currsor for the job infomation
					$i = 0;
					
					$jobsinfo [$count] [] = $project;
					$jobsinfo [$count] [] = $job;
					echo $job . "\n";
					echo $count . "\n";
					
					foreach ( $jobstatus as $onestatus ) {
						
						$one = substr ( $onestatus, (strpos ( $onestatus, ':' ) + 1), strlen ( $onestatus ) );
						
						if ($i == 0)
							$jobsinfo [$count] [] = trim ( $one );
						if ($i == 8) {
							// echo Function_Tools::ChangeToDatetime(trim($one));
							$jobsinfo [$count] [] = Function_Tools::ChangeToDatetime ( trim ( $one ) );
						}
						if ($i == 6)
							$jobsinfo [$count] [] = trim ( $one );
						$i ++;
					}
					
					$jobsinfo [$count] [] = trim ( $scheduleinfo );
					
					fclose ( $errorStream );
					fclose ( $stream );
					$count ++;
				}
				
				// break;
			}
		}
		return $jobsinfo;
	}
	public function SetScheduleTime($hostname, $projectname, $jobname, $username, $password, $scheduletime) {
		$bash = 'source ~/.bash_profile';
		
		$connection = ssh2_connect ( $hostname, 22 );
		
		if (ssh2_auth_password ( $connection, $username, $password )) {
			echo "Authentication Successful!\n";
		} else {
			die ( 'Authentication Failed...' );
		}
		
		$command = "crontab -l > /tmp/dsadm.crontab;echo \"" . $scheduletime . "\" /home/dsadm/runjob.sh " . $projectname . ' ' . $jobname . ">>/tmp/dsadm.crontab;crontab /tmp/dsadm.crontab";
		
		echo $command;
		$stream = ssh2_exec ( $connection, $bash . ';' . $command );
		
		$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
		
		stream_set_blocking ( $errorStream, true );
		stream_set_blocking ( $stream, true );
		
		fclose ( $errorStream );
		fclose ( $stream );
	}
	
	
	
	public function DelScheduleTime($hostname, $projectname, $jobname, $username, $password, $scheduletime) {
		$bash = 'source ~/.bash_profile';
		
		$connection = ssh2_connect ( $hostname, 22 );
		
		if (ssh2_auth_password ( $connection, $username, $password )) {
			echo "Authentication Successful!\n";
		} else {
			die ( 'Authentication Failed...' );
		}
		
		$command = 'crontab -l';
		
		$stream = ssh2_exec ( $connection, $bash . ';' . $command );
		$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
		
		
		
		
		stream_set_blocking ( $errorStream, true );
		stream_set_blocking ( $stream, true );
		
		$jobslist = explode ( "\n", stream_get_contents ( $stream ) );
		
		

		
		foreach ( $jobslist as $item ) {
		
			
			if (  stripos($item,$projectname) !== FALSE && stripos($item,$jobname) !== FALSE
				&& stripos($item,$scheduletime) !== FALSE)
			{

			
				continue;
			} 
			else 
				$newjobslist[] = $item;
		}
		
		
		fclose ( $errorStream );
		fclose ( $stream );
		$i = 0;
		foreach ( $newjobslist as $item){
			if ( $i == 0 ){
				$command = 'echo "'.$item.'"'.' > /tmp/dsadm.crontab; '; 
				$connection = ssh2_connect ( $hostname, 22 );
				
		
				
				if (ssh2_auth_password ( $connection, $username, $password )) {
					echo "Authentication Successful!\n";
				} else {
					die ( 'Authentication Failed...' );
				}
				$stream = ssh2_exec ( $connection, $bash . ';' . $command );
				$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
				
				stream_set_blocking ( $errorStream, true );
				stream_set_blocking ( $stream, true );
				
				fclose ( $errorStream );
				fclose ( $stream );
				
				echo $command."\n";
			}
			else 
			{
				$command = 'echo "'.$item.'"'.' >> /tmp/dsadm.crontab; ';
				
				$connection = ssh2_connect ( $hostname, 22 );
				
				if (ssh2_auth_password ( $connection, $username, $password )) {
					echo "Authentication Successful!\n";
				} else {
					die ( 'Authentication Failed...' );
				}
				$stream = ssh2_exec ( $connection, $bash . ';' . $command );
				$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
				
				stream_set_blocking ( $errorStream, true );
				stream_set_blocking ( $stream, true );
				
				fclose ( $errorStream );
				fclose ( $stream );
				
				echo $command."\n";
			}
			
			$i++;
		}
		
		$conmand = 'crontab /tmp/dsadm.crontab';
		
		$stream = ssh2_exec ( $connection, $bash . ';' . $command );
		$errorStream = ssh2_fetch_stream ( $stream, SSH2_STREAM_STDERR );
		
		stream_set_blocking ( $errorStream, true );
		stream_set_blocking ( $stream, true );
		
		fclose ( $errorStream );
		fclose ( $stream );
		
		
		
		
		
	}
}

?>


