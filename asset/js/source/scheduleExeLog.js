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
		data[i].prjExeResultA= "1111111";
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
