;

/**
 * @fileoverview This file contains all the js needed for the login page
 */


/**
 * This function is used to show the forgot password dialog window. It is
 * called when the user clicks on the forgot password link.
 */
function showforgotpw() {
  $('#forgotpwwindow').dialog('open');
}


/**
 * This function is used to show the register new account dialog window. It is
 * called when the user clicks on the register link.
 */
function showregister() {
  $('#registerwindow').dialog('open');
}


/**
 * This function will submit the user input data for a new account to
 * the server using ajax post.
 */
function submitregister() {
  $.post('../../users/register', {
    account: $('#registeraccount').val(),
    password: $('#registerpw').val(),
    confirm: $('#registerpwconfirm').val(),
    email: $('#registeremail').val()
  },
  function(data, status) {
    if (status == 'success') {
      if (data.status == 'success') {
        $('#registerwindow').dialog('close');
        alert('註冊成功，請輸入帳號密碼');
      } else {
        alert(data.data);
      }
    } else {
      alert('註冊連線失敗');
    }
  });
}


/**
 * This function will submit the user input data for retrieving their account
 * to the server using ajax post. If it succeeds, an e-mail will be sent to
 * their registered e-mail account.
 */
function submitforgotpw() {
  $.post('../../users/forgotpw', {
    account: $('#forgotpwaccount').val()
  },
  function(data, status) {
    if (status == 'success') {
      if (data.status == 'success') {
        $('#registerwindow').dialog('close');
        alert('已經將密碼寄到該帳號綁定之信箱');
      } else {
        alert(data.data);
      }
    } else {
      alert('連線失敗');
    }
  });

}


/**
 * Initialization for the js objects.
 */
$(document).ready(function() {
  $('#forgotpwwindow').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto',
    modal: true
  });
  $('#registerwindow').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto',
    modal: true
  });
});
