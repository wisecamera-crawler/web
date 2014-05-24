<html>
<head>
<title><?php echo $title;?></title>
</head>
<body>


<table border="1"id="userlist"   align="left">
   <tbody>
	<tr><td colspan=2>user's Account</td></tr>		    
   </tbody>
</table>
<div style="height:100px;overflow:auto;">
<table border="1"id="userLoginLog"  align="center">
   <tbody>
	<tr><td>Time</td><td>User</td><td >IP login/logout record</td></tr>		    
   </tbody>
</table>
</div>

<script>
$(document).ready(function(){
		$.post("<?php echo base_url();?>index.php/log/getUsers",
			{

			},
			function(data,status){
			if(status='success'){

			for(var i=0; i<data.length;i++){	
			//			alert( data[i].user_id );
			$("#userlist").append("<tr>" +
				"<td>" + data[i].user_id   + "</td>" +
				"<td><input type='checkbox' name='userchkbox' value='"+ data[i].user_id +"'></td>" +
				"</tr>");
			}
			}else{
			alert("sth wrong");
			}



			$('input[type="checkbox"][name="userchkbox"]').click(function()
					{
					if (this.checked)
					{
						$("tr[class='"+$(this).val()+"']").show();
//						$("#userLoginLog").find("'"+ $(this).val()  +"'").show();
						
					}else
					{
						$("tr[class='"+$(this).val()+"']").hide();
					}
					});
			});

		$.post("<?php echo base_url();?>index.php/log/userLogin/user001",
				{

				},
				function(data,status){
				if(status='success'){

				for(var i=0; i<data.length;i++){
				//                      alert( data[i].user_id );
				$("#userLoginLog").append("<tr class="+data[i].user_id+" hidden='true'>" +
					"<td>" + data[i].timestamp   + "</td>" +
					"<td>" + data[i].user_id   + "</td>" +
					"<td>" + data[i].action +" from "+data[i].ip   + "</td>" +

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
