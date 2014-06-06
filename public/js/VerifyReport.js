function VerifyDailyReport()
{
	
	
//	 $("#test tr").each
//	 (
//			 function()
//			 {
//				$(this).find("td").each
//				(
//						function()
//						{
//						alert($(this).html);
//						}
//				)
//			 }
//	 )
	
//	$("table tr").each(function()
//			 {
//			  alert($(this).find("td").eq(1).html());
//			 })
	
	
	$("table").find("th, td").each(function(){
	    alert($(this).text());
	});
	
	
}

