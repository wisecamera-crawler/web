<html>
<head>
<title><?php echo $title;?></title>
</head>
<body>

<div style = 'float:left;width:25%'>
<div class='greenwindow'>
<table  class="fancytable" border="1"id="userlist"   align="left">
   <tbody>
	<tr><th colspan=2>使用者帳號</th></tr>
   </tbody>
</table>
<button onclick='selectAll();'>全選</button><button onclick='deselectAll();'>全不選</button>
</div>
</div>

<div style='float: left;width:75%;height:100%;'>
<div class='greenwindowscroll'>
<table class="fancytable" border="1"id="userLoginLog"  align="center">
   <tbody>
	<tr><th>時間</th><th>使用者</th><th>IP登入/登出記錄</th></tr>		    
   </tbody>
</table>
<div>
<div>
<div>

<script>
function selectAll(){
	$('input[type="checkbox"][name="userchkbox"]').prop('checked',true).trigger('change');
}
function deselectAll(){
	$('input[type="checkbox"][name="userchkbox"]').prop('checked',false).trigger('change');
}
$(document).ready(function(){

//		$("#selectAll").attr('checked', true);
//		$('input[type="checkbox"][name="userchkbox"]').each(function(){
//			this.attr('checked', true);
//		});

		$.post("<?php echo base_url();?>index.php/log/getUsers",
			{

			},
			function(data,status){
			if(status='success'){

			for(var i=0; i<data.length;i++){	
			//			alert( data[i].user_id );
			$("#userlist").append("<tr>" +
				"<td>" + data[i].user_id   + "</td>" +
				"<td><input type='checkbox' name='userchkbox' value='"+ data[i].user_id +"' checked='true'></td>" +
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
					}else
					{
						$("tr[class='"+$(this).val()+"']").hide();
					}
					});

			 $('input[type="checkbox"][name="userchkbox"]').change(function(){
				if (this.checked)
                                        {
                                                $("tr[class='"+$(this).val()+"']").show();

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
				$("#userLoginLog").append("<tr class="+data[i].user_id+" >" +
					"<td>" + data[i].timestamp   + "</td>" +
					"<td>" + data[i].user_id   + "</td>" +
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
