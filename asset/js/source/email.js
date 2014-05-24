;

/**
 * @fileoverview This file contains all the js needed for the setemail page
 */


/**
 * This function is used when the user clicks the submit button after input
 *     an notification email. It will display human readable messages about
 *     the result of the insertion.
 */
function submitEmail() {
  var email = $('#emailinput').val();
  $.ajax({
    url: '../../email/insertemail',
    type: 'POST',
    data: {
      email: email
    },
    async: false,

    success: function(data) {
      if (data.status == 'success') {
        alert('電子郵件帳號加入成功');
        location.reload(true);
      } else {
        alert('此電子郵件帳號已經在資料庫中');
      }
    },
    error: function(xhr, status, error) {
      alert('伺服器錯誤，請重新載入頁面');
    }
  });

}

/**
 * This function is used when the user clicks on a delete button. It will
 *     send an ajax call to the server and try to delete the email from
 *     the notification list.
 * @param {Object} obj The dom element of the delete button.
 */


function deleteEmail(obj) {
  var email = $(obj).parent().parent().find('td:nth-child(1)').text();
  if (confirm('確定刪除電子郵件帳號: ' + email + ' ?')) {
    $.ajax({
      url: '../../email/deleteemail',
      type: 'POST',
      data: {
        email: email
      },
      async: false,

      success: function(data) {
        if (data.status == 'success') {
          alert('電子郵件帳號刪除成功');
          location.reload(true);
        } else {
          alert('無法刪除該帳號');
        }
      },
      error: function(xhr, status, error) {
        alert('伺服器錯誤，請重新載入頁面');
      }
    });
  }
}
