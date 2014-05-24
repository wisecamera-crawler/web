<html>
<head>
<title><?php echo $title;?></title>
</head>
<body>

<div style = 'float:left;width:35%'>
<div class='greenwindow'>
<table class="fancytable" border="1"id="proxylist"   align="left"  class="fancytable">
   <tbody>
	<tr><th>Proxy Server</th><th>IP</th><th>狀態</th><th>啟用</th></tr>		    
   </tbody>
</table>
<input type="button" value="確認" style="float:right" id="btuProxyList">

</div>
</div>


<div style='float: left;width:65%;'>
<div class='greenwindowscroll'>
<table class="fancytable" border="1"id="prjEditLog"  class="fancytable" align="center">
   <tbody>
	<tr><th>時間</th><th >IP</th><th>佈署記錄</th></tr>		    
   </tbody>
</table>
<div>
<div>

<script>

$("#btuProxyList").click(function(){
	var cbxVehicle = {};
	$('input:checkbox[name="proxyEnable"]').each(
	function(i) { 
		if(this.checked){
		cbxVehicle[i] = 1; 
		}else{
		cbxVehicle[i] = 0; 
		}
	});


	$.ajax({
		url: "<?php echo base_url();?>index.php/proxy/work",
		type:'POST',               
		data: {account:cbxVehicle},
		error:function(){alert('Ajax request');},
		success: function(res){
//			alert('Ajax success!');		

//			alert(res);	
		location.reload();

		}		
	});	



});

$(document).ready(function(){
		$.post("<?php echo base_url();?>index.php/proxy/getProxyList",
			{



			},
			function(data,status){
			if(status='success'){

			for(var i=0; i<data.length;i++){	
			//			alert( data[i].user_id );

			if(data[i].status == "disable"){
			var chkStr = "<input type='checkbox' name='proxyEnable' value='"+data[i].proxy_ip+"' checkede='false'>";
			}else{
			var chkStr = "<input type='checkbox' name='proxyEnable' value='"+data[i].proxy_ip+"' checked='true'>";
			}
			$("#proxylist").append("<tr>" +
				"<td>" + (i+1)  + "</td>" +
				"<td>" + data[i].proxy_ip   + "</td>" +
				"<td>" + data[i].status  + "</td>" +
				"<td>" + chkStr + "</td>" +
				"</tr>");
			}
			}else{
				alert("sth wrong");
			}



			});

		$.post("<?php echo base_url();?>index.php/log/deploy",
				{

				},
				function(data,status){
				if(status='success'){

				for(var i=0; i<data.length;i++){
				//                      alert( data[i].user_id );
				$("#prjEditLog").append("<tr>" +
					"<td>" + data[i].timestamp   + "</td>" +
					"<td>" + data[i].ip   + "</td>" +
					"<td>" + data[i].action + "</td>" +
					"</tr>");

				}

				}else{
				alert("sth wrong");
				}
				});



});






</script>

</body>
</html>
