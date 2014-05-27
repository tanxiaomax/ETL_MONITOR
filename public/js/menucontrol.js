
function showLocale(objD)
{
    var str,colorhead,colorfoot;
    var yy = objD.getYear();
    if(yy<1900) yy = yy+1900;
    var MM = objD.getMonth()+1;
    if(MM<10) MM = '0' + MM;
    var dd = objD.getDate();
    if(dd<10) dd = '0' + dd;
    var hh = objD.getHours();
    if(hh<10) hh = '0' + hh;
    var mm = objD.getMinutes();
    if(mm<10) mm = '0' + mm;
    var ss = objD.getSeconds();
    if(ss<10) ss = '0' + ss;
    var ww = objD.getDay();
    if  ( ww==0 )  colorhead="<font color=\"#FF0000\">";
    if  ( ww > 0 && ww < 6 )  colorhead="<font color=\"#373737\">";
    if  ( ww==6 )  colorhead="<font color=\"#008000\">";
    if  (ww==0)  ww="Sunday";
    if  (ww==1)  ww="Monday";
    if  (ww==2)  ww="Tuesday";
    if  (ww==3)  ww="Wednesday";
    if  (ww==4)  ww="Thursday";
    if  (ww==5)  ww="Friday";
    if  (ww==6)  ww="Saturday";
    colorfoot="</font>"
    str = colorhead + yy + "-" + MM + "-" + dd + " " + hh + ":" + mm + ":" + ss + "  " + ww + colorfoot;
    return(str);
}
function tick()
{
    var today;
    today = new Date();
     document.getElementById("date").innerHTML = showLocale(today);
    window.setTimeout("tick()", 1000);
}

function menuControl()
{
        var currentUrl = document.URL;
        var elems =document.getElementsByClassName("submenu");
        if(currentUrl=="http://etl_monitor.zf/configpage/hostinfo" || currentUrl=="http://etl_monitor.zf/configpage/monitoringjobinfo")
        {      
            for(var i=0;i<elems.length;i++)
            {
                
                if(i==2)
                {
                    elems[i].style.display="";
                }
                else
                {
                    elems[i].style.display="none";
                }
            }
        }
        else if(currentUrl=="http://etl_monitor.zf/monitorpage/monitorresult")
        {
        	 document.getElementById('header').style.height = '73px';
        	for(var i=0;i<elems.length;i++)
            {
                elems[i].style.display="none";
            }
        }
}

window.onload=function(){
      menuControl();
      tick();
     }