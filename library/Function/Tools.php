<?php
class Function_Tools
{
	public static function  IsWeekend($date)
	{
		$time=strtotime($date);
		if (date('w',$time) == 6 || date('w',$time) == 0)
		{
			return true;
		}
		else
			return false;
	}
	public static function CountMetricID($value, $arrays)
	{
		$count = 0;
		
		
		foreach($arrays as $array) 
		{
			foreach ($array as $item)
			{
				
				if(in_array($value, $item))
				{
					if(!Function_Tools::IsWeekend($item['DMMS_DATE']))
						$count++;
					else 
						continue;
				}
				
			}
		
	
			
		}
		return $count;
		
	}
	
	
	public static function IsAccept($value,$avgvalue)
	{
		if ($avgvalue == 0) 
		{
			echo 'Error average value';
			return false;
		}
		$threshold = $value/$avgvalue;
		
		if ($threshold < 0.8 or $threshold > 1.2 ) 
		{
			return false;
		}
		else 
		{
			return true;
		}
		
	}
	
	public static function ChangeToDatetime($lastruntime)
	{//Thu Jul 24 01:05:23 2014
		//$length = strlen($LASTRUNTIME);
		//$firspacepos = strpos($LASTRUNTIME, ' ');
		
		if (strlen($lastruntime) == 24)
		{
			$month = substr($lastruntime, 4,3);
			$date = substr($lastruntime, 8,2);
			$time = substr($lastruntime, 11,8);
			$year = substr($lastruntime, 20,4);
			
			switch ($month)
			{
				case "Jan":
					$month = '01';
					break;
				case "Feb":
					$month = '02';
					break;
				case "Mar":
					$month = '03';
					break;
				case "Apr":
					$month = '04';
					break;
				case "May":
					$month = '05';
					break;
				case "Jun":
					$month = '06';
					break;
				case "Jul":
					$month = '07';
					break;
				case "Aug":
					$month = '08';
					break;
				case "Sep":
					$month = '09';
					break;
				case "Oct":
					$month = '10';
					break;
				case "Nov":
					$month = '11';
					break;
				case "Dec":
					$month = '12';
					break;
					
				default:
					echo "Error date format";
			
			
			}
			
			if ( strlen(trim($date)) == 1)
			{
				$date = '0' . trim($date); 
			}
			
			$datetime = $year.'-'.$month.'-'.$date.' '.$time;
		}
		else 
		{
			$datetime ='1900-00-00 00:00:00';	
		}
		
		return $datetime;
		
		
	}
	
	
	public static function DisplayCrontabTime($crontabtime)
	{

		if ( $crontabtime == 'Unschedule')
			return 'The job has not been scheduled';
		
		
		$raw = explode(' ',$crontabtime);
		$pattern1 = '/^\*\/[\d]/';
		$pattern2 = '/^(\d-\d)\/[1-9]/';
		$pattern3 = '/\*/';
		$pattern4 = '/^-?\d*$/';
		$pattern5 = '/^[\d]*\-[\d]*$/';
		
		$result = array('','','','','');
		
		$timeunit = array('minute','hour','date','month','day');
		
		
		for($i = 0;$i < 5;$i++)
		{
	
			if (strpos($raw[$i],',') !=  null)
			{
				$array = explode(',',$raw[$i]);
				
				foreach ($array as $one)
				{
					
// 					if (preg_match($pattern2, $one) ) {
// 						echo $one."zhengze"."\n";
// 					}
// 					echo $one."\n";

					if (preg_match($pattern4, $one) )
					{
						$result[$i] = $result[$i] .$one . $timeunit[$i]. ' '; 
						continue;
					}
					
					if (preg_match($pattern5, $one) )
					{
						$temp = explode('-', $one);
						$result[$i] = $result[$i] .$temp[0].' to '.$temp[1] . $timeunit[$i]. ' '; 
						continue;
					} 
					
					if (preg_match($pattern1, $one) )
					{
						$temp = substr($one, 2,1);
						$result[$i] = $result[$i] .'every '.$temp . $timeunit[$i]. ' ';
						continue;
					}
					if (preg_match($pattern2, $one) )
					{
						$start = substr($one, 0,1);
						$end = substr($one, 2,1);
						$interval = substr($one,4,1);
						$result[$i] = $result[$i] .'every '.$interval.' '.$timeunit[$i].
						' from '. $start .' '.$timeunit[$i]. ' to ' .$end .$timeunit[$i] . ' '; 
						continue;
					}
					
					
						
				}
			}
			
			else 
			{
				if (preg_match($pattern4, $raw[$i]) )
				{
					$result[$i] = $result[$i] .$raw[$i] . $timeunit[$i]. ' ';
					continue;
				}
					
				if (preg_match($pattern5, $raw[$i]) )
				{
					$temp = explode('-', $raw[$i]);
					$result[$i] = $result[$i] .$temp[0].' to '.$temp[1] . $timeunit[$i]. ' ';
					continue;
				}
					
				if (preg_match($pattern1, $raw[$i]) )
				{
					$temp = substr($raw[$i], 2,1);
					$result[$i] = $result[$i] .'every '.$temp . $timeunit[$i]. ' ';
					continue;
				}
				if (preg_match($pattern2, $raw[$i]) )
				{
					$start = substr($raw[$i], 0,1);
					$end = substr($raw[$i], 2,1);
					$interval = substr($raw[$i],4,1);
					$result[$i] = $result[$i] .'every '.$interval.' '.$timeunit[$i].
					' from '. $start .' '.$timeunit[$i]. ' to ' .$end .$timeunit[$i] . ' ';
					continue;
				}
// 				if (preg_match($pattern2, $raw[$i])) {
// 					echo $raw[$i]."zhengze"."\n";
// 				}
// 				echo $raw[$i]."\n";
			}
		}
		$i = 0;
		
		$time = '';
		foreach ($result as $item)
		{
			 $time = $time . $item;	
		}
		
		return $time;
	
		
	}
	
	
}


Function_Tools::DisplayCrontabTime('45 11 * * 0');
?>