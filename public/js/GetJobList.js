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


function getproject(hostinfo)
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
		//var smallClassName=document.getElementById("projects");
		try
		{
			$.getJSON("/config-page/getmonitoringproject/q/" + hostinfo, function(result){
				var projects = "";
			    $.each(result, function(i, field){
			    	projects += '<option value="'+ field +'">'+ field + '</option>';
			        
//			          var objOption=document.createElement("OPTION");
//			          objOption.value = field;
//			          objOption.text = field;
//			          smallClassName.add(objOption);
			          
			         
			    });
			    $("#projects").html(projects);
			    $("#jobs").html('<option></option>')
			    
			});
			
			
		}
		catch (e)
		{
			alert("Can't connect to server:\n")
		}
//		
	}

	
	
	
}
//
//
function getjob(hostinfo,projectinfo)
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
			$.getJSON("/config-page/getmonitoringjob/host/" + hostinfo + "/project/" + projectinfo, function(result){
				var projects = "";
			    $.each(result, function(i, field){
			    	projects += '<option value="'+ field +'">'+ field + '</option>';
			    });
			    $("#jobs").html(projects);
			  });
		}
		catch (e)
		{
			alert("Can't connect to server:\n")
		}
//		
	}

	
	
	
}



function getValue()
{
	try
	{
	var host = document.getElementById('hosts');
	var project = document.getElementById('projects');
	var job = document.getElementById('jobs');
	//alert(project.value);
	
	var url = window.location.href;
//	alert("url"+url);
//	xmlHttp.open("POST",url);
//	xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//	
//	
//     var jobinfo = "host="+host.value+"&project="+project.value+"&job="+job.value;
//     jobinfo = encodeURI(encodeURI(jobinfo));
//     xmlHttp.send(jobinfo);
//     alert("right");
	
	
	
	 $.post("/config-page/monitoringjobinfo",
			  {
			    host:host.value,
			    project:project.value,
			    job:job.value
			  },
			  function(data,status){
			    alert(status);
			  });
			
	}
	catch (e)
	{
		alert("error");
	}
	
		
	
	
}

