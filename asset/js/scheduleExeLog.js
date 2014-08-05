/**
 * @fileoverview This file contains all the js needed for the schedule
 * execution log page
 */


/**
 * This function is called when the page have loaded completely
 */
$(document).ready(function() {
  /*To get the log of schedule execution in DB */
  $.post('../../log/scheduleExe', {

  },
  function(data, status) {
    if (status = 'success') {

      for (var i = 0; i < data.length; i++) {
        //                      alert( data[i].user_id );
	if(data[i].prjExeResultA.length > 20){
		var oriStr = data[i].prjExeResultA;
		var tmpStr = data[i].prjExeResultA;
		tmpStr =  tmpStr.substring(0,10) + "...";

		data[i].prjExeResultA= "<a href=\"#\" onclick=\"window.open('test?aaa=123', 'Yahoo', config='height=500,width=500');\">"+tmpStr+"</a>";
		data[i].prjExeResultA= "<a href=\"#\" onclick=\"alert('"+oriStr+"');\">"+tmpStr+"</a>";
	}
        $('#scheduleExeLog').append('<tr>' +
            '<td>' + data[i].prjID + '</td>' +
            '<td>' + data[i].prjName + '</td>' +
            '<td>' + data[i].prjExeST + '</td>' +
            '<td>' + data[i].prjExeResult + '</td>' +
            '<td>' + data[i].prjExeResultA + '</td>' +
            '<td>' + data[i].prjExeET + '</td>' +
            '</tr>');

      }
    } else {
      alert('sth wrong');
    }
  });



});
