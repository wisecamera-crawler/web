$("#btuProxyList").click(function(){var a={};$('input:checkbox[name="proxyEnable"]').each(function(c){a[c]=this.checked?1:0});$.ajax({url:"../../proxy/work",type:"POST",data:{account:a},error:function(){alert("Ajax request")},success:function(a){location.reload()}})});
$(document).ready(function(){$.post("../../proxy/getProxyList",{},function(a,c){for(var b=0;b<a.length;b++){var d="disable"==a[b].status?"<input type='checkbox' name='proxyEnable' value='"+a[b].proxy_ip+"' checkede='false'>":"<input type='checkbox' name='proxyEnable' value='"+a[b].proxy_ip+"' checked='true'>";$("#proxylist").append("<tr><td>"+(b+1)+"</td><td>"+a[b].proxy_ip+"</td><td>"+a[b].status+"</td><td>"+d+"</td></tr>")}});$.post("../../log/deploy",{},function(a,c){for(var b=0;b<a.length;b++)$("#prjEditLog").append("<tr><td>"+
a[b].timestamp+"</td><td>"+a[b].ip+"</td><td>"+a[b].action+"</td></tr>")})});