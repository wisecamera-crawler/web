function selectAll(){$('input[type="checkbox"][name="userchkbox"]').prop("checked",!0).trigger("change")}function deselectAll(){$('input[type="checkbox"][name="userchkbox"]').prop("checked",!1).trigger("change")}
$(document).ready(function(){$.post("../../log/getUsers",{},function(b,c){for(var a=0;a<b.length;a++)$("#userlist").append("<tr><td>"+b[a].user_id+"</td><td><input type='checkbox' name='userchkbox' value='"+b[a].user_id+"' checked='true'></td></tr>");$('input[type="checkbox"][name="userchkbox"]').click(function(){this.checked?$("tr[class='"+$(this).val()+"']").show():$("tr[class='"+$(this).val()+"']").hide()});$('input[type="checkbox"][name="userchkbox"]').change(function(){this.checked?$("tr[class='"+
$(this).val()+"']").show():$("tr[class='"+$(this).val()+"']").hide()})});$.post("../../log/query",{},function(b,c){for(var a=0;a<b.length;a++)$("#prjEditLog").append("<tr class="+b[a].user_id+" ><td>"+b[a].timestamp+"</td><td>"+b[a].user_id+"</td><td>"+b[a].ip+"</td><td>"+b[a].action+"</td></tr>")})});
