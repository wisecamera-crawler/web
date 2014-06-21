function getprojectfromrowid(a,b){var c=$(a).attr("id"),c=parseInt(c.replace("projectrow",""));return b[c]}function sortwithid(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.project_id.localeCompare(b.project_id)}function sortwithplatform(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.platform.localeCompare(b.platform)}function sortwithyear(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.year>b.year?1:a.year<b.year?-1:0}
function sortwithprojectname(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.name.localeCompare(b.name)}function sortwithprojecthost(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.leader.localeCompare(b.leader)}function sortwithclass(a,b,c){a=getprojectfromrowid(a,c);b=getprojectfromrowid(b,c);return a.type.localeCompare(b.type)}
function sortwithfunction(a,b,c){var d=$("#projecttable"),e=d.find("tr").get();e.splice(0,1);e.sort(function(c,d){return a(c,d,b)});c&&e.reverse();$.each(e,function(a,b){d.children("tbody").append(b)})}
function sortprojects(a,b){var c=$(a).attr("value"),d,e;$(".sortcontrol").each(function(){var a=$(this).find(".arrow-up").length,b=$(this).find(".arrow-down").length;0<a&&(d=$(this).attr("value"),e=!1);0<b&&(d=$(this).attr("value"),e=!0)});$(".sortcontrol").find(".arrow-up").remove();$(".sortcontrol").find(".arrow-down").remove();var f=!0,g=!1;d==c&&(f=!e,g=!0);"year"==c?sortwithfunction(sortwithyear,b,!f):"id"==c?sortwithfunction(sortwithid,b,!f):"class"==c?sortwithfunction(sortwithclass,b,!f):"projectname"==
c?sortwithfunction(sortwithprojectname,b,!f):"platform"==c?sortwithfunction(sortwithplatform,b,!f):"projecthost"==c&&sortwithfunction(sortwithprojecthost,b,!f);f?a.append('<div class="arrow-down"></div>'):a.append('<div class="arrow-up"></div>');g||(a.find(".sorticon").remove(),$('.sortcontrol[value="'+d+'"]').append('<div class="sorticon"></div>'))}
function plottrendgraph(a,b,c,d,e,f){if(void 0==d)alert("\u6b64\u5c08\u6848\u5c1a\u7121\u8cc7\u6599");else if(0==d.length)alert("\u6b64\u5c08\u6848\u5c1a\u7121\u8cc7\u6599");else{for(var g=0;g<d.length;++g)d[g][1]=parseInt(d[g][1]);d.sort(function(a,b){return a[0].localeCompare(b[0])});"all"!=e&&d.length>parseInt(e)&&(d=d.splice(0,parseInt(e)));$.jqplot(a,[d],{title:f,axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer,tickOptions:{formatString:"%b %d"},label:b},yaxis:{tickRenderer:$.jqplot.AxisTickRenderer,
tickOptions:{show:!0},label:c}},series:[{pointLabels:{show:!0,edgeTolerance:-50}}],animate:!0,animateReplot:!0})}}var projectStatus={};
function loadStatusViewer(a){$.ajax({url:"../../projects/getstatusgraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){projectStatus=a},error:function(a,c,d){alert(a.responseText);alert(d)}});$("#statusviewer .statusviewtype").off("change");$("#statusviewer .statusviewtype").change(function(){plotStatusViewer()});$("#statusviewer .showplot").off("click");$("#statusviewer .showplot").click(function(){plotStatusViewer()});$("#statusviewer").dialog("option","title",a.project_id+
" \u72c0\u614b\u6b77\u53f2\u8cc7\u6599");$("#statusviewer").dialog("open");plotStatusViewer()}function extractData(a,b){var c=[],d;for(d in projectStatus){var e=[d,parseInt(projectStatus[d][a][b])];c.push(e)}return c}
function plotStatusViewer(){if(0==projectStatus.length)alert("\u6b64\u5c08\u6848\u5c1a\u7121\u8cc7\u6599");else{var a=$("#statusviewer .statusviewtype :selected").val(),b=fillZeroDates(extractData(a,"all_success")),c=fillZeroDates(extractData(a,"success_update")),d=fillZeroDates(extractData(a,"no_change")),e=fillZeroDates(extractData(a,"all_fail")),f=fillZeroDates(extractData(a,"cannot_get_data")),g=fillZeroDates(extractData(a,"can_not_resolve")),l=fillZeroDates(extractData(a,"no_proxy")),a=fillZeroDates(extractData(a,
"proxy_error")),h=Math.floor((Date.parse(b[b.length-1][0])-Date.parse(b[0][0]))/864E5),m=parseInt($("#statusgraph").css("width")),k=1;0<h&&(k=Math.ceil(h/(m/50)));$("#statusgraph").empty();b=$.jqplot("statusgraph",[b,c,d,e,f,g,l,a],{title:"\u72c0\u614b\u5716\u8868",axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer,tickRenderer:$.jqplot.CanvasAxisTickRenderer,tickOptions:{formatString:"%b %d"},tickInterval:k+" day"},yaxis:{min:0}},highlighter:{show:!0},animate:!0,animateReplot:!0,legend:{show:!0,location:"e",
placement:"inside",labels:"\u6210\u529f \u8cc7\u6599\u6539\u8b8a\u4e14\u532f\u5165\u6210\u529f \u8cc7\u6599\u7121\u6539\u8b8a \u5931\u6557 \u7121\u6cd5\u53d6\u5f97\u8cc7\u6599\u9801\u9762 \u89e3\u6790\u5931\u6557 \u7121Proxy\u53ef\u7528 Proxy\u932f\u8aa4".split(" ")}});b.series[0].show=$('#statusviewer .showplot[value="all_success"').prop("checked");b.series[1].show=$('#statusviewer .showplot[value="success_update"').prop("checked");b.series[2].show=$('#statusviewer .showplot[value="no_change"').prop("checked");
b.series[3].show=$('#statusviewer .showplot[value="fail"').prop("checked");b.series[4].show=$('#statusviewer .showplot[value="cannot_get_data"').prop("checked");b.series[5].show=$('#statusviewer .showplot[value="can_not_resolve"').prop("checked");b.series[6].show=$('#statusviewer .showplot[value="no_proxy"').prop("checked");b.series[7].show=$('#statusviewer .showplot[value="proxy_error"').prop("checked");b.replot()}}var crawlstatus={};
function loadcrawlerviewer(a){$.ajax({url:"../../projects/getproxygraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){crawlstatus=a},error:function(a,b,c){alert(a.responseText);alert(c)}});var b=[],c;for(c in crawlstatus)b=b.concat(crawlstatus[c]);b.sort(function(a,b){var c=new Date(a.day),d=new Date(b.day);return c.getTime()<d.getTime()?-1:c.getTime()>d.getTime()?1:0});for(var d=[],e=0;e<b.length;++e)d.hasOwnProperty(b[e].day)?(d[b[e].day].all_success+=b[e].all_success,
d[b[e].day].success_update+=b[e].success_update,d[b[e].day].no_change+=b[e].no_change,d[b[e].day].fail+=b[e].fail):(d[b[e].day]={},d[b[e].day].all_success=b[e].all_success,d[b[e].day].success_update=b[e].success_update,d[b[e].day].no_change=b[e].no_change,d[b[e].day].fail=b[e].fail),d[b[e].day].day=b[e].day;b=[];for(c in d)b=b.concat(d[c]);crawlstatus.all=b;$("#crawlerviewer .crawlerselect").empty();for(c in crawlstatus)"all"==c?$("#crawlerviewer .crawlerselect").append($('<option value="all">\u5168\u90e8</option>')):
$("#crawlerviewer .crawlerselect").append($("<option></option>").attr("value",c).text(c));$("#crawlerviewer .crawlerselect").off("change");$("#crawlerviewer .crawlerselect").change(function(){plotcrawlerviewer()});$("#crawlerviewer .showplot").off("click");$("#crawlerviewer .showplot").click(function(){plotcrawlerviewer()});$("#crawlerviewer").dialog("option","title",a.project_id+" crawler\u6b77\u53f2\u8cc7\u6599");$("#crawlerviewer").dialog("open");plotcrawlerviewer()}
function extractWithPropertyName(a,b){for(var c=[],d=0;d<a.length;++d)c.push([a[d].day,a[d][b]]);return c}function fillZeroDates(a){if(void 0===a||0==a.length)return a;var b=new Date(a[0][0]),c=[];c.push(a[0]);for(var d=1;d<a.length;++d){b=new Date(a[d-1][0]);b.setDate(b.getDate()+1);for(var e=new Date(a[d][0]);b<e;b.setDate(b.getDate()+1)){var f=new Date(b);f.setHours(0,0,0,0);f=[f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate(),0];c.push(f)}c.push(a[d])}return c}
function plotcrawlerviewer(){$("#crawlergraph").empty();if(0==crawlstatus.length)alert("\u6b64\u5c08\u6848\u5c1a\u7121\u8cc7\u6599");else{var a=$("#crawlerviewer .crawlerselect").val(),b=fillZeroDates(extractWithPropertyName(crawlstatus[a],"all_success")),c=fillZeroDates(extractWithPropertyName(crawlstatus[a],"success_update")),d=fillZeroDates(extractWithPropertyName(crawlstatus[a],"no_change")),a=fillZeroDates(extractWithPropertyName(crawlstatus[a],"fail")),e=Math.floor((Date.parse(b[b.length-1][0])-
Date.parse(b[0][0]))/864E5),f=parseInt($("#crawlergraph").css("width")),g=1;0<e&&(g=Math.ceil(e/(f/50)));b=$.jqplot("crawlergraph",[b,c,d,a],{title:"Proxy status",axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer,tickOptions:{formatString:"%b %d"},tickInterval:g+" day"},yaxis:{min:0}},highlighter:{show:!0},legend:{show:!0,location:"e",placement:"inside",labels:["\u6210\u529f","\u8cc7\u6599\u6539\u8b8a\u4e14\u532f\u5165\u6210\u529f","\u8cc7\u6599\u7121\u6539\u8b8a","\u5931\u6557"]},animate:!0,animateReplot:!0});
b.series[0].show=$('#crawlerviewer .showplot[value="all_success"').prop("checked");b.series[1].show=$('#crawlerviewer .showplot[value="success_update"').prop("checked");b.series[2].show=$('#crawlerviewer .showplot[value="no_change"').prop("checked");b.series[3].show=$('#crawlerviewer .showplot[value="fail"').prop("checked");b.replot()}}var downloaddata=[],downloadsinglefiledata=[],organizeddownloadsinglefiledata={};
function loaddownloadviewer(a){$.ajax({url:"../../projects/getdownloadgraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){downloaddata=a},error:function(a,b,c){alert(a.responseText)}});$.ajax({url:"../../projects/getdownloadsinglefilegraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){downloadsinglefiledata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});
for(var b={},c=0;c<downloadsinglefiledata.length;++c)b[downloadsinglefiledata[c].name]=!0;for(var d in b){for(var e=[],c=0;c<downloadsinglefiledata.length;++c)downloadsinglefiledata[c].name==d&&e.push(downloadsinglefiledata[c]);organizeddownloadsinglefiledata[d]=e}$("#downloadviewer").find(".threadnamelist").empty();var c="",f;for(f in b)c+='<option value="'+f+'">'+f+"</option>";$("#downloadviewer").find(".threadnamelist").append(c);$("#downloadviewer").dialog("option","title",a.project_id+"download\u6b77\u53f2\u8cc7\u6599");
$("#downloadviewer").dialog("open");plotdownloadviewer();$("#downloadviewer").find(".displaytype").off("change");$("#downloadviewer").find(".displaytype").change(function(){0>$("#downloadviewer").find(".displaytype :selected").val().indexOf("total")?$("#downloadviewer").find(".singlethreadpanel").show():$("#downloadviewer").find(".singlethreadpanel").hide();plotdownloadviewer()});$("#downloadviewer").find(".threadnamelist").off("change");$("#downloadviewer").find(".threadnamelist").change(function(){plotdownloadviewer()});
$("#downloadviewer").find(".numdatas").off("change");$("#downloadviewer").find(".numdatas").change(function(){plotdownloadviewer()})}
function plotdownloadviewer(){$("#downloadgraph").empty();var a=$("#downloadviewer").find(".displaytype :selected").val();if("totalfiles"==a){for(var a=[],b=0;b<downloaddata.length;++b)a.push([downloaddata[b].timestamp,downloaddata[b].totalfiles]);plottrendgraph("downloadgraph","\u65e5\u671f","\u7e3d\u6a94\u6848\u6578",a,$("#downloadviewer .numdatas :selected").val(),"Download \u5716\u8868")}else if("totaldownloads"==a){a=[];for(b=0;b<downloaddata.length;++b)a.push([downloaddata[b].timestamp,downloaddata[b].totaldownloads]);
plottrendgraph("downloadgraph","\u65e5\u671f","\u7e3d\u4e0b\u8f09\u6b21\u6578",a,$("#downloadviewer .numdatas :selected").val(),"Download \u5716\u8868")}else if("singledownloads"==a){for(var c=$("#downloadviewer").find(".threadnamelist :selected").val(),a=[],b=0;b<organizeddownloadsinglefiledata[c].length;++b)a.push([organizeddownloadsinglefiledata[c][b].timestamp,organizeddownloadsinglefiledata[c][b].count]);plottrendgraph("downloadgraph","\u65e5\u671f","\u55ae\u4e00\u6a94\u6848\u4e0b\u8f09\u6b21\u6578",
a,$("#downloadviewer .numdatas :selected").val(),"Download \u5716\u8868")}}var issuetrackerdata=[];
function loadissuetrackerviewer(a){$.ajax({url:"../../projects/getissuetrackergraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){issuetrackerdata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});$("#issuetrackerviewer").dialog("option","title",a.project_id+" issue tracker\u6b77\u53f2\u8cc7\u6599");$("#issuetrackerviewer").dialog("open");plotissuetrackerviewer();$("#issuetrackerviewer").find(".displaytype").off("change");
$("#issuetrackerviewer").find(".displaytype").change(function(){plotissuetrackerviewer()});$("#issuetrackerviewer").find(".numdatas").off("change");$("#issuetrackerviewer").find(".numdatas").change(function(){plotissuetrackerviewer()})}
function plotissuetrackerviewer(){$("#issuetrackergraph").empty();var a=$("#issuetrackerviewer").find(".displaytype :selected").val();if("totalthreads"==a){for(var a=[],b=0;b<issuetrackerdata.length;++b)a.push([issuetrackerdata[b].timestamp,issuetrackerdata[b].topic]);plottrendgraph("issuetrackergraph","\u65e5\u671f","\u4e3b\u984c\u7e3d\u6578",a,$("#issuetrackerviewer .numdatas :selected").val(),"Issue Tracker \u5716\u8868")}else if("totalreplies"==a){a=[];for(b=0;b<issuetrackerdata.length;++b)a.push([issuetrackerdata[b].timestamp,
issuetrackerdata[b].article]);plottrendgraph("issuetrackergraph","\u65e5\u671f","\u56de\u61c9\u7e3d\u6578",a,$("#issuetrackerviewer .numdatas :selected").val(),"Issue Tracker \u5716\u8868")}else if("totalaccounts"==a){a=[];for(b=0;b<issuetrackerdata.length;++b)a.push([issuetrackerdata[b].timestamp,issuetrackerdata[b].account]);plottrendgraph("issuetrackergraph","\u65e5\u671f","\u4e0d\u540c\u5e33\u865f\u7e3d\u6578",a,$("#issuetrackerviewer .numdatas :selected").val(),"Issue Tracker \u5716\u8868")}}
var vcsdata=[],vcscommiterdata=[],organizedvcscommiterdata={};
function loadvcsviewer(a){$.ajax({url:"../../projects/getvcsgraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){vcsdata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});$.ajax({url:"../../projects/getvcscommitergraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){vcscommiterdata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});
for(var b={},c=0;c<vcscommiterdata.length;++c)b[vcscommiterdata[c].timestamp]=!0;for(var d in b){for(var e=[],c=0;c<vcscommiterdata.length;++c)vcscommiterdata[c].timestamp==d&&e.push(vcscommiterdata[c]);organizedvcscommiterdata[d]=e}$("#vcsviewer").find(".datelist").empty();var c="",f;for(f in b)c+='<option value="'+f+'">'+f+"</option>";$("#vcsviewer").find(".datelist").append(c);$("#vcsviewer").dialog("option","title",a.project_id+" vcs\u6b77\u53f2\u8cc7\u6599");$("#vcsviewer").dialog("open");plotvcsviewer();
$("#vcsviewer").find(".displaytype").off("change");$("#vcsviewer").find(".displaytype").change(function(){0>$("#vcsviewer").find(".displaytype :selected").val().indexOf("total")?$("#vcsviewer").find(".contributionpanel").show():$("#vcsviewer").find(".contributionpanel").hide();plotvcsviewer()});$("#vcsviewer").find(".datelist").off("change");$("#vcsviewer").find(".datelist").change(function(){plotvcsviewer()});$("#vcsviewer").find(".numdatas").off("change");$("#vcsviewer").find(".numdatas").change(function(){plotvcsviewer()})}
function plotvcsviewer(){$("#vcsgraph").empty();var a=$("#vcsviewer").find(".displaytype :selected").val();"usercontribution"!=a&&$("#vcsgraph").css("height","450px");if("totalcommits"==a){for(var b=[],a=0;a<vcsdata.length;++a)b.push([vcsdata[a].timestamp,vcsdata[a].commit]);plottrendgraph("vcsgraph","\u65e5\u671f","\u7e3dcommit\u6578",b,$("#vcsviewer .numdatas :selected").val(),"VCS \u5716\u8868")}else if("totallines"==a){b=[];for(a=0;a<vcsdata.length;++a)b.push([vcsdata[a].timestamp,vcsdata[a].line]);
plottrendgraph("vcsgraph","\u65e5\u671f","\u7e3d\u884c\u6578",b,$("#vcsviewer .numdatas :selected").val(),"VCS \u5716\u8868")}else if("totalfiles"==a){b=[];for(a=0;a<vcsdata.length;++a)b.push([vcsdata[a].timestamp,vcsdata[a].file]);plottrendgraph("vcsgraph","\u65e5\u671f","\u7e3d\u6a94\u6848\u6578",b,$("#vcsviewer .numdatas :selected").val(),"VCS \u5716\u8868")}else if("totalfilesize"==a){b=[];for(a=0;a<vcsdata.length;++a)b.push([vcsdata[a].timestamp,vcsdata[a].size]);plottrendgraph("vcsgraph","\u65e5\u671f",
"\u7e3d\u6a94\u6848\u5927\u5c0f(KB)",b,$("#vcsviewer .numdatas :selected").val(),"VCS \u5716\u8868")}else if("totalusers"==a){b=[];for(a=0;a<vcsdata.length;++a)b.push([vcsdata[a].timestamp,vcsdata[a].user]);plottrendgraph("vcsgraph","\u65e5\u671f","\u7e3d\u8ca2\u737b\u8005\u6578",b,$("#vcsviewer .numdatas :selected").val(),"VCS \u5716\u8868")}else if("usercontribution"==a){var b=$("#vcsviewer").find(".datelist :selected").val(),c=[],d=[],e=[],a=organizedvcscommiterdata[b].length;$("#vcsgraph").css("height",
Math.max(450,70*a)+"px");for(a=0;a<organizedvcscommiterdata[b].length;++a)c.push([organizedvcscommiterdata[b][a]["new"],organizedvcscommiterdata[b][a].commiter]),d.push([organizedvcscommiterdata[b][a].modify,organizedvcscommiterdata[b][a].commiter]),e.push([organizedvcscommiterdata[b][a]["delete"],organizedvcscommiterdata[b][a].commiter]);$.jqplot("vcsgraph",[c,d,e],{seriesDefaults:{renderer:$.jqplot.BarRenderer,pointLabels:{show:!0,location:"e",edgeTolerance:-15},shadowAngle:135,rendererOptions:{barDirection:"horizontal"}},
series:[{label:"Add"},{label:"Modify"},{label:"Delete"}],axes:{yaxis:{renderer:$.jqplot.CategoryAxisRenderer}},legend:{show:!0,placement:"inside",rendererOptions:{numberRows:1},location:"ne",marginTop:"15px"}})}}var wikidata=[],wikisinglethreaddata=[],organizedwikisinglethreaddata={};
function loadwikiviewer(a){$.ajax({url:"../../projects/getwikigraphdata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){wikidata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});$.ajax({url:"../../projects/getwikigraphsinglethreaddata",type:"POST",data:{project_id:a.project_id},async:!1,success:function(a){wikisinglethreaddata=a},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});
for(var b={},c=0;c<wikisinglethreaddata.length;++c)b[wikisinglethreaddata[c].title]=!0;for(var d in b){for(var e=[],c=0;c<wikisinglethreaddata.length;++c)wikisinglethreaddata[c].title==d&&e.push(wikisinglethreaddata[c]);organizedwikisinglethreaddata[d]=e}$("#wikiviewer").find(".threadnamelist").empty();var c="",f;for(f in b)c+='<option value="'+f+'">'+f+"</option>";$("#wikiviewer").find(".threadnamelist").append(c);$("#wikiviewer").dialog("option","title",a.project_id+" wiki\u6b77\u53f2\u8cc7\u6599");
$("#wikiviewer").dialog("open");plotwikiviewer();$("#wikiviewer").find(".displaytype").off("change");$("#wikiviewer").find(".displaytype").change(function(){0>$("#wikiviewer").find(".displaytype :selected").val().indexOf("total")?$("#wikiviewer").find(".singlethreadpanel").show():$("#wikiviewer").find(".singlethreadpanel").hide();plotwikiviewer()});$("#wikiviewer").find(".threadnamelist").off("change");$("#wikiviewer").find(".threadnamelist").change(function(){plotwikiviewer()});$("#wikiviewer").find(".numdatas").off("change");
$("#wikiviewer").find(".numdatas").change(function(){plotwikiviewer()})}
function plotwikiviewer(){$("#wikigraph").empty();var a=$("#wikiviewer").find(".displaytype :selected").val();if("totalthreads"==a){for(var a=[],b=0;b<wikidata.length;++b)a.push([wikidata[b].timestamp,wikidata[b].pages]);plottrendgraph("wikigraph","\u65e5\u671f","\u7e3d\u7bc7\u6578",a,$("#wikiviewer .numdatas :selected").val(),"Wiki \u5716\u8868")}else if("totallines"==a){a=[];for(b=0;b<wikidata.length;++b)a.push([wikidata[b].timestamp,wikidata[b].line]);plottrendgraph("wikigraph","\u65e5\u671f",
"\u7e3d\u884c\u6578",a,$("#wikiviewer .numdatas :selected").val(),"Wiki \u5716\u8868")}else if("totalupdates"==a){a=[];for(b=0;b<wikidata.length;++b)a.push([wikidata[b].timestamp,wikidata[b].update]);plottrendgraph("wikigraph","\u65e5\u671f","\u7e3d\u66f4\u65b0\u6578",a,$("#wikiviewer .numdatas :selected").val(),"Wiki \u5716\u8868")}else if("singlethreadlines"==a){for(var c=$("#wikiviewer").find(".threadnamelist :selected").val(),a=[],b=0;b<organizedwikisinglethreaddata[c].length;++b)a.push([organizedwikisinglethreaddata[c][b].timestamp,
organizedwikisinglethreaddata[c][b].line]);plottrendgraph("wikigraph","\u65e5\u671f","\u55ae\u4e00\u7bc7\u884c\u6578",a,$("#wikiviewer .numdatas :selected").val(),"Wiki \u5716\u8868")}else if("singlethreadupdates"==a){c=$("#wikiviewer").find(".threadnamelist :selected").val();a=[];for(b=0;b<organizedwikisinglethreaddata[c].length;++b)a.push([organizedwikisinglethreaddata[c][b].timestamp,organizedwikisinglethreaddata[c][b].update]);plottrendgraph("wikigraph","\u65e5\u671f","\u55ae\u4e00\u7bc7\u66f4\u65b0\u6578",
a,$("#wikiviewer .numdatas :selected").val(),"Wiki \u5716\u8868")}}
function hideunfittingprojects(a){var b={},c=0,d=0,e=[];$("#filteryearwindow input:checked").each(function(){e.push($(this).val())});for(c=0;c<a.length;c++)b[c]=!0;for(c=0;c<a.length;c++){for(var f=!1,d=0;d<e.length;d++)if(e[d]==a[c].year){f=!0;break}f||delete b[c]}e=[];$("#filterclasswindow input:checked").each(function(){e.push($(this).val())});for(var g in b){f=!1;for(c=0;c<a.length;c++)if(e[c]==a[g].type){f=!0;break}f||delete b[g]}e=[];$("#filterplatformwindow input:checked").each(function(){e.push($(this).val())});
for(g in b){f=!1;for(c=0;c<a.length;c++)if(e[c]==a[g].platform.replace(" ","_")){f=!0;break}f||delete b[g]}c=$("#idtb").val();if(0<c.length)for(g in b)0>a[g].project_id.indexOf(c)&&delete b[g];c=$("#projectnametb").val();if(0<c.length)for(g in b)0>a[g].name.indexOf(c)&&delete b[g];for(c=0;c<a.length;c++)c in b?$("#projectrow"+c).show():$("#projectrow"+c).hide()}function compareNumbers(a,b){return a-b}
$(document).ready(function(){var a=[];$.ajax({url:"../../projects/getgenericdata",type:"GET",async:!1,success:function(b){a=b},error:function(){alert("\u7121\u6cd5\u53d6\u5f97\u5c08\u6848\u57fa\u672c\u8cc7\u6599\uff0c\u8acb\u91cd\u65b0\u6574\u7406\u6b64\u9801\u9762")}});$("#wikiviewer").dialog({autoOpen:!1,height:"auto",width:"auto"});$("#vcsviewer").dialog({autoOpen:!1,height:"auto",width:"auto"});$("#issuetrackerviewer").dialog({autoOpen:!1,height:"auto",width:"auto"});$("#downloadviewer").dialog({autoOpen:!1,
height:"auto",width:"auto"});$("#crawlerviewer").dialog({autoOpen:!1,height:"auto",width:"auto"});$("#statusviewer").dialog({autoOpen:!1,height:"auto",width:"auto"});for(var b={},c=[],d=0,d=0;d<a.length;d++)b[a[d].year]=!0;for(var e in b)b.hasOwnProperty(e)&&c.push(e);c.sort(compareNumbers);b="";for(d=0;d<c.length;d++)b+='<input class="filtercheck" type="checkbox" value='+c[d]+" checked>"+c[d]+"\u5e74\u5ea6</input>";$("#filteryearwindow").append(b);b={};c=[];for(d=0;d<a.length;d++)b[a[d].type]=!0;
for(e in b)b.hasOwnProperty(e)&&c.push(e);b="";for(d=0;d<c.length;d++)b+='<input type="checkbox" class="filtercheck" value='+c[d]+" checked>"+c[d]+"</input>";$("#filterclasswindow").append(b);b={};c=[];for(d=0;d<a.length;d++)b[a[d].platform]=!0;for(e in b)b.hasOwnProperty(e)&&c.push(e);e="";for(d=0;d<c.length;d++)e+='<input type="checkbox" class="filtercheck" value='+c[d].replace(" ","_")+" checked>"+c[d]+"</input>";$("#filterplatformwindow").append(e);e="";for(d=0;d<a.length;d++)e+='<tr class="alt" id="projectrow'+
d+'">',e+="<td>"+a[d].year+"</td><td>"+a[d].type+"</td><td>"+a[d].project_id+"</td><td>"+a[d].name+"</td><td>"+a[d].leader+'</td><td><a href="'+a[d].url+'">'+a[d].platform+"</a></td>",e+='<td><span class="wikiviewertext" projectidx='+d+">\u7e3d\u5171"+a[d].wiki_pages+"\u7bc7<br>\u7e3d\u5171"+a[d].wiki_line+"\u884c<br>\u7e3d\u5171"+a[d].wiki_update+"\u6b21\u66f4\u65b0</span></td>",e+='<td><span class="issuetrackerviewertext" projectidx='+d+">\u7e3d\u5171"+a[d].issue_topic+"\u7b46\u4e3b\u984c<br>\u7e3d\u5171"+
a[d].issue_post+"\u7bc7\u6587\u7ae0<br>\u7e3d\u5171"+a[d].issue_user+"\u4e0d\u540c\u5e33\u865f\u53c3\u4e88<br>\u7248\u672c\u63a7\u5236\uff1a"+a[d].vcs_type+"</span></td>",e+='<td><span class="vcsviewertext" projectidx='+d+">\u7e3d\u5171"+a[d].vcs_commit+"\u6b21commit<br>\u7e3d\u5171"+a[d].vcs_line+"\u884c<br>\u7e3d\u5171"+a[d].vcs_file+"\u500b\u6a94\u6848<br>\u7e3d\u5171"+a[d].vcs_size+"<br>\u7e3d\u5171"+a[d].vcs_user+"\u4e0d\u540c\u5e33\u865f\u53c3\u4e88</span></td>",e+='<td><span class="downloadviewertext" projectidx='+
d+">\u7e3d\u5171"+a[d].dl_file+"\u500b\u6a94\u6848<br>\u7e3d\u5171"+a[d].dl_count+"\u6b21\u4e0b\u8f09</span></td>",e+="<td>",e="googlecode"==a[d].platform?e+("Starred by "+a[d].star+" users"):"github"==a[d].platform?e+("Star: "+a[d].star+" <br>Watched: "+a[d].watch+"<br>Fork: "+a[d].fork):"sourceforge"==a[d].platform?e+("5-Stars: "+a[d]["5-star"]+"<br>4-Stars: "+a[d]["4-star"]+"<br>3-Stars: "+a[d]["3-star"]+"<br>2-Stars: "+a[d]["2-star"]+"<br>1-Star: "+a[d]["1-star"]):e+"N/A",e+="</td>",e+="<td>"+
a[d].last_update+"</td>",e+='<td><span class="crawlertext" projectidx='+d+">"+a[d].proxy_ip+"</span></td>",e+='<td><span class="statustext" projectidx='+d+">"+a[d].status+"</span></td></tr>";$("#projecttable").append(e);$("#projecttable").find(".wikiviewertext").click(function(){loadwikiviewer(a[$(this).attr("projectidx")])});$("#projecttable").find(".vcsviewertext").click(function(){loadvcsviewer(a[$(this).attr("projectidx")])});$("#projecttable").find(".issuetrackerviewertext").click(function(){loadissuetrackerviewer(a[$(this).attr("projectidx")])});
$("#projecttable").find(".downloadviewertext").click(function(){loaddownloadviewer(a[$(this).attr("projectidx")])});$("#projecttable").find(".crawlertext").click(function(){loadcrawlerviewer(a[$(this).attr("projectidx")])});$("#projecttable").find(".statustext").click(function(){loadStatusViewer(a[$(this).attr("projectidx")])});$(".filtercheck").change(function(){hideunfittingprojects(a)});$("#idtb").on("change keyup paste",function(){hideunfittingprojects(a)});$("#projectnametb").on("change keyup paste",
function(){hideunfittingprojects(a)});$("#filteryearwindow").dialog({title:"\u904e\u6ffe\u5e74\u5ea6",height:"auto",width:"auto",autoOpen:!1,draggable:!1,modal:!0,position:{my:"top",at:"bottom",of:$("#filteryearbutton")},buttons:[{text:"\u5168\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!0);hideunfittingprojects(a)}},{text:"\u5168\u4e0d\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!1);hideunfittingprojects(a)}},{text:"\u78ba\u5b9a",click:function(){$(this).dialog("close")}}]});
$("#filteryearbutton").click(function(){$("#filteryearwindow").dialog("open")});$("#filterclasswindow").dialog({title:"\u904e\u6ffe\u985e\u5225",height:"auto",width:"auto",autoOpen:!1,draggable:!1,modal:!0,position:{my:"top",at:"bottom",of:$("#filterclassbutton")},buttons:[{text:"\u5168\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!0);hideunfittingprojects(a)}},{text:"\u5168\u4e0d\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!1);hideunfittingprojects(a)}},
{text:"\u78ba\u5b9a",click:function(){$(this).dialog("close")}}]});$("#filterclassbutton").click(function(){$("#filterclasswindow").dialog("open")});$("#filterplatformwindow").dialog({title:"\u904e\u6ffe\u5e73\u53f0",height:"auto",width:"auto",autoOpen:!1,draggable:!1,modal:!0,position:{my:"top",at:"bottom",of:$("#filterplatformbutton")},buttons:[{text:"\u5168\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!0);hideunfittingprojects(a)}},{text:"\u5168\u4e0d\u9078",click:function(){$(this).find(".filtercheck").prop("checked",
!1);hideunfittingprojects(a)}},{text:"\u78ba\u5b9a",click:function(){$(this).dialog("close")}}]});$("#filterplatformbutton").click(function(){$("#filterplatformwindow").dialog("open")});sortwithfunction(sortwithid,a,!1);$(".sortcontrol").click(function(){sortprojects($(this),a)});loadColumnSelection("#columnselectwindow","#projecttable");$("#columnselectwindow").dialog({title:"\u9078\u64c7\u986f\u793a\u6b04\u4f4d",height:"auto",width:"auto",autoOpen:!1,draggable:!1,modal:!0,position:{my:"top",at:"bottom",
of:$("#selectcolumnbutton")},buttons:[{text:"\u5168\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!0);hideColumns()}},{text:"\u5168\u4e0d\u9078",click:function(){$(this).find(".filtercheck").prop("checked",!1);hideColumns()}},{text:"\u78ba\u5b9a",click:function(){$(this).dialog("close")}}]})});
function loadColumnSelection(a,b){$(a).empty();$(b+" th").each(function(){$(a).append('<input type="checkbox" class="filtercheck" value='+($(this).index()+1)+" checked>"+$(this).text()+"</input>")});$(a).find(".filtercheck").change(function(){hideColumns()})}function selectColumn(){$("#columnselectwindow").dialog("open")}
function hideColumns(){$("#columnselectwindow").find(".filtercheck").each(function(){var a=$(this).prop("checked"),b=$(this).val();a?$("#projecttable th:nth-child("+b+"),#projecttable td:nth-child("+b+")").show():$("#projecttable th:nth-child("+b+"),#projecttable td:nth-child("+b+")").hide()})};
