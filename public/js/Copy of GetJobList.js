var xmlHttp = creatXMLHttpRequestObject();

//creates an XMLHttpRequest instance
function creatXMLHttpRequestObject()  
{  
	try
		{
	   // Firefox, Opera 8.0+, Safari
		
	xmlHttp=new XMLHttpRequest();
		}
	catch (e)
		{
	  // Internet Explorer
	   try
		  {
		  
	xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		  }
	   catch (e)
		  {
		  try
			 {
			 
	xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			 }
		  catch (e)
			 {
			 alert("You browser doesn't support AJAX");
			 return false;
			 }
		  }
		}
		
	return xmlHttp;
}  


function getproject(hostinfo,projectinfo)
{
//	var date = new Date();
//	var hour = date.getHours();
//	// demonstrating the if statement
//	if (hour >= 22 || hour <= 5) 
//	  document.write("Goodnight, world!");
//	else
//	  document.write("Hello, world!!!!");
	
	
	if (xmlHttp)
	{

		try
		{
			
			var url="http://etl_monitor.zf/configpage/getmonitoringproject";
			url=url+"?q="+hostinfo

			
			//callback function
			
			
				
			xmlHttp.onreadystatechange = stateChanged;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);	
				
		}
		catch (e)
		{
			alert("Can't connect to server:\n")
		}
//		
	}

	
	
	
}

//
function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
	
	
	
    
 } 
}