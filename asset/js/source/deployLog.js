/**
 * @fileoverview This file contains all the js needed for the proxy
 * servers on or off log page
 */


/**
 * This function is used when users want to chanege proxy
 * status. when the confirm button clicks the web will send
 * the changes to the server and log it. it will refresh when
 * it has done and reload the newest logs.
 */
$('#btuProxyList').click(function() {
  var cbxVehicle = {};
  $('input:checkbox[name="proxyEnable"]').each(
      function(i) {
        if (this.checked) {
          cbxVehicle[i] = 1;
        } else {
          cbxVehicle[i] = 0;
        }
      });


  $.ajax({
    url: '../../proxy/work',
    type: 'POST',
    data: {
      account: cbxVehicle
    },
    error: function() {
      alert('Ajax request');
    },
    success: function(res) {
      location.reload();
    }
  });



});


/**
 * This function is called when the page have loaded completely
 */
$(document).ready(function() {
  /*get proxy list in DB */
  $.post('../../proxy/getProxyList', {



  },
  function(data, status) {
    if (status = 'success') {

      for (var i = 0; i < data.length; i++) {

        if (data[i].status == 'disable') {
          var chkStr = "<input type='checkbox' name='proxyEnable' value='" +
              data[i].proxy_ip + "' checkede='false'>";
        } else {
          var chkStr = "<input type='checkbox' name='proxyEnable' value='" +
              data[i].proxy_ip + "' checked='true'>";
        }
        $('#proxylist').append('<tr>' +
            '<td>' + (i + 1) + '</td>' +
            '<td>' + data[i].proxy_ip + '</td>' +
            '<td>' + data[i].status + '</td>' +
            '<td>' + chkStr + '</td>' +
            '</tr>');
      }
    } else {
      alert('sth wrong');
    }



  });

  /*To get the log of proxy deploy in DB */
  $.post('../../log/deploy', {

  },
  function(data, status) {
    if (status = 'success') {

      for (var i = 0; i < data.length; i++) {
        $('#prjEditLog').append('<tr>' +
            '<td>' + data[i].timestamp + '</td>' +
            '<td>' + data[i].ip + '</td>' +
            '<td>' + data[i].action + '</td>' +
            '</tr>');

      }

    } else {
      alert('sth wrong');
    }
  });



});
