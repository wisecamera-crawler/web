function addZeroForHourMinute(c){return 1==c.length?"0"+c:c}
function insertSchedule(){var c=$("#perioddl :selected").val(),d=$("#targettype :selected").val(),e=0,a="",b={year:"",type:"",project_ids:""};"weekly"==c?(e=$("#weekdaydl :selected").val(),a=addZeroForHourMinute($("#hour :selected").val())+":"+addZeroForHourMinute($("#minute :selected").val())+":00"):a="daily"==c?addZeroForHourMinute($("#hour :selected").val())+":"+addZeroForHourMinute($("#minute :selected").val())+":00":$("#datepick").val()+" "+addZeroForHourMinute($("#hour :selected").val())+":"+
addZeroForHourMinute($("#minute :selected").val())+":00";"yearclass"==d?(b.year=$("#proj_year_dl :selected").val(),b.type=$("#proj_class_dl :selected").val()):b.project_ids=$("#targetids").val();alert(c+" "+d+" "+e+" "+a+" "+b.year+" "+b.type+" "+b.project_ids);$.ajax({url:"../../schedules/insertschedule",type:"POST",async:!1,data:{period:c,type:d,schedule:e,time:a,target:b},success:function(a){"success"==a.status?(alert("\u65b0\u589e\u6392\u7a0b\u6210\u529f"),location.reload(!0)):alert("\u65b0\u589e\u6392\u627f\u5931\u6557\uff0c\u539f\u56e0\u5982\u4e0b:\n"+
a.errorMessage)},error:function(){alert("\u7121\u6cd5\u65b0\u589e\u6392\u7a0b")}})}
function loadScheduleTable(){var c={};$.ajax({url:"../../schedules/getschedules",type:"GET",async:!1,success:function(a){c=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u6392\u7a0b\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});for(var d="<tr><th>\u9031\u671f</th><th>\u6642\u9593</th><th>\u76ee\u6a19</th><th>\u522a\u9664</th></tr>",e=0;e<c.active.length;++e)var a=c.active[e],d=d+("<tr><td>"+a.period+"<td>"+a.time+"</td><td>"+a.group+'</td><td><button value="'+
a.schedule_id+'">\u522a\u9664</button></td></tr>');$("#activescheduletable").append(d);d="<tr><th>\u9031\u671f</th><th>\u6642\u9593</th><th>\u76ee\u6a19</th><th>\u522a\u9664</th></tr>";for(e=0;e<c.inactive.length;++e)a=c.inactive[e],d+="<tr><td>"+a.period+"<td>"+a.time+"</td><td>"+a.group+'</td><td><button value="'+a.schedule_id+'">\u522a\u9664</button></td></tr>';$("#inactivescheduletable").append(d);$("#activescheduletable tr td button,#inactivescheduletable tr td button").click(function(){var a=
$(this).val(),c=$(this).parent().parent(),d="";$(c).children().each(function(){3!=$(this).index&&(d+=" "+$(this).text())});confirm("\u78ba\u5b9a\u522a\u9664"+d+" \u7684\u6392\u7a0b\u5167\u5bb9?")&&$.ajax({url:"../../schedules/deleteschedule",type:"POST",data:{schedule_id:a},async:!1,success:function(a){"success"==a.status?location.reload(!0):alert("\u522a\u9664\u5931\u6557\uff0c\u539f\u56e0\u5982\u4e0b:\n"+a.errorMessage)},error:function(){alert("\u522a\u9664\u5931\u6557")}})})}
function updateoptions(){var c=$("#perioddl").val();$("#optioncell span").each(function(){$(this).hide()});"weekly"==c?$("#weeklyoptions").show():"one_time"==c&&$("#one_timeoptions").show()}
$(document).ready(function(){var c=[];$.ajax({url:"../../projects/getids",type:"GET",async:!1,success:function(a){c=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});var d=[],e=[];$.ajax({url:"../../projects/getvalidprojectyears",type:"GET",async:!1,success:function(a){d=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u5e74\u5206\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});
$.ajax({url:"../../projects/getvalidprojecttypes",type:"GET",async:!1,success:function(a){e=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u985e\u5225\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});$("#proj_year_dl").append($("<option></option>").attr("value","all").text("\u5168\u90e8"));for(var a=0;a<d.length;a++)$("#proj_year_dl").append($("<option></option>").attr("value",""+d[a]).text(""+d[a]));$("#proj_class_dl").append($("<option></option>").attr("value","all").text("\u5168\u90e8"));
for(a=0;a<e.length;a++)$("#proj_class_dl").append($("<option></option>").attr("value",e[a]).text(e[a]));$("#datepick").datepicker();$("#targetids").autocompleteprojectid(c);updateoptions();$("#perioddl").change(function(){updateoptions()});for(var b="",a=1;12>=a;a++)b+="<option value = "+a+">"+a+"</option>";$("#one_timeoptionsmonthdl").append(b);b="";for(a=1;31>=a;a++)b+="<option value = "+a+">"+a+"</option>";$("#one_timeoptionsdaydl").append(b);b="";for(a=0;24>a;a++)b+="<option value = "+a+">"+a+
"</option>";$("#hour").append(b);b="";for(a=0;60>a;a++)b+="<option value = "+a+">"+a+"</option>";$("#minute").append(b);$("#targettype").change(function(){var a=$("#targettype :selected").val();$("#yearclassform").hide();$("#idform").hide();"yearclass"==a?$("#yearclassform").show():$("#idform").show()});loadScheduleTable()});

