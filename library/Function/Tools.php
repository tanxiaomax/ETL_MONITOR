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
	
	
}
?>