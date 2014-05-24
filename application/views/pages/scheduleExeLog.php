<html>
<head>
<title><?php echo $title;?></title>
</head>
<body>

<div style='float: left;width:100%;'>
<div class='greenwindowscroll'>
<table class="fancytable" border="1"id="scheduleExeLog"  align="center">
   <tbody>
	<tr><th>專案代碼</th><th>專案名稱</th><th >專案開始執行時間</th><th>執行結果</th><th>執行結果分析</th><th>完成時間</th></tr>		    
   </tbody>
</table>
<div>
<div>

<script>
$(document).ready(function(){
		
		$.post("<?php echo base_url();?>index.php/log/scheduleExe",
				{

				},
				function(data,status){
				if(status='success'){

				for(var i=0; i<data.length;i++){
				//                      alert( data[i].user_id );
				$("#scheduleExeLog").append("<tr>" +
					"<td>" + data[i].prjID   + "</td>" +
					"<td>" + data[i].prjName   + "</td>" +
					"<td>" + data[i].prjExeST   + "</td>" +
					"<td>" + data[i].prjExeResult + "</td>" +
					"<td>" + data[i].prjExeResultA + "</td>" +
					"<td>" + data[i].prjExeET + "</td>" +
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
