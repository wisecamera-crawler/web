/**
 * @fileoverview This file contains all the js needed for the schedule edit
 * log page
 */


/**
 * This function is used when the user clicks select-all button in user list
 * and the check box in user list is checked
 */
function selectAll() {
  $('input[type="checkbox"][name="userchkbox"]').prop('checked', true).trigger(
      'change');
}


/**
 * This function is used when the user clicks deselect-all button in user list
 * and the check boxes in user list is all unchecked
 */
function deselectAll() {
  $('input[type="checkbox"][name="userchkbox"]').prop('checked', false).trigger(
      'change');
}


/**
 * This function is called when the page have loaded completely
 */
$(document).ready(function() {
  /*get user list in DB */
  $.post('../../log/getUsers', {

  },
  function(data, status) {
    if (status = 'success') {

      for (var i = 0; i < data.length; i++) {
        $('#userlist').append('<tr>' +
            '<td>' + data[i].user_id + '</td>' +
            "<td><input type='checkbox' name='userchkbox' value='" +
            data[i].user_id + "' checked='true'></td>" + '</tr>');
      }
    } else {
      alert('sth wrong');
    }


    /*To control whether the log shows or hides */
    $('input[type="checkbox"][name="userchkbox"]').click(function() {
      if (this.checked) {
        $("tr[class='" + $(this).val() + "']").show();

      } else {
        $("tr[class='" + $(this).val() + "']").hide();
      }
    });
    /*To control whether the log shows or hides */
    $('input[type="checkbox"][name="userchkbox"]').change(function() {
      if (this.checked) {
        $("tr[class='" + $(this).val() + "']").show();

      } else {
        $("tr[class='" + $(this).val() + "']").hide();
      }

    });
  });

  /*To get the log of schedule edit in DB */
  $.post('../../log/scheduleEdit', {

  },
  function(data, status) {
    if (status = 'success') {

      for (var i = 0; i < data.length; i++) {
        //                      alert( data[i].user_id );
        $('#prjEditLog').append('<tr class=' + data[i].user_id + ' >' +
            '<td>' + data[i].timestamp + '</td>' +
            '<td>' + data[i].user_id + '</td>' +
            '<td>' + data[i].ip + '</td>' +
            '<td>' + data[i].action + '</td>' +
            '</tr>');

      }
    } else {
      alert('sth wrong');
    }
  });



});
