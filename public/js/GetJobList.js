     $.ajax({
            type: "get",//使用get方法访问后台
            dataType: "json",//返回json格式的数据
            url: "http://etl_monitor.zf/configpage/getmonitoringjob",//要访问的后台地址
            data: "q=" + hostinfo,//要发送的数据
            complete :function(){$("#load").hide();},//AJAX请求完成时隐藏loading提示
            success: function(msg){//msg为返回的数据，在这里做数据绑定
                var data = msg.table;
                $.each(data, function(i, n){
                    
                });