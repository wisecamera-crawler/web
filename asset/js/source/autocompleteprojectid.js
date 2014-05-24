/**
 * This jquery extension is for the textbox to autocomplete the projects' ids
 *     separated by commas.
 * @this jQuery
 * @param {Array} idlist This is an array of string project ids.
 * @return {jQuery} This returns the jQuery object that contains all the
 *     selected textboxes.
 */
$.fn.autocompleteprojectid = function(idlist) {
  return this.each(function() {
    $(this).autocomplete({
      source: idlist
    });
    var obj = $(this);
    $(this).on('change keyup paste', function(e) {
      if (e.which < 37 || e.which > 40) {
        var tokenids = $(this).val().split(/[\s,]/);
        var lasttoken = tokenids[tokenids.length - 1];
        var possibleids = [];
        for (var idx = 0; idx < idlist.length; idx++) {
          if (idlist[idx].indexOf(lasttoken) >= 0) {
            possibleids.push(idlist[idx]);
          }
        }
        $(this).autocomplete('option', 'source', possibleids);
        $(this).autocomplete('search', lasttoken);
        $(this).off('autocompletefocus');
        $(this).on('autocompletefocus', function(event, ui) {
          var newval = '';
          for (var idx = 0; idx < tokenids.length - 1; idx++) {
            newval += tokenids[idx] + ',';
          }
          newval += ui.item.label;
          $(this).val(newval);
          event.preventDefault();
        });
        $(this).off('autocompleteselect');
        $(this).on('autocompleteselect', function(event, ui) {
          var newval = '';
          for (var idx = 0; idx < tokenids.length - 1; idx++) {
            newval += tokenids[idx] + ',';
          }
          newval += ui.item.label;
          $(this).val(newval);
          event.preventDefault();
        });

      }
    });
  });
};


/**
 * This jquery extension is for the button to show a input window for the user
 *     in order to input the attributes of the project.
 * @this jQuery
 * @return {void} This function returns void.
 */
$.fn.addnewprojectwindow = function() {
  this.newprojectwindow = $("<div class='newprojectwindow'><center><span>" +
      "年度:<input type = 'text' class='newprojyear'></input>類別:" +
      "<input type = 'text' class='newprojclass'></input>代碼:" +
      "<input type = 'text' class='newprojid'></input>專案名稱:" +
      "<input type = 'text' class='newprojname'></input></input>平台:" +
      "<input type = 'text' class='newprojplatform'></input></span>" +
      "</center><center><button class='newprojok'>確認</button>" +
      "<button class='newprojcancel'>取消</button></center></div>");
  $('body').append(this.newprojectwindow);
  $(this.newprojectwindow).dialog(
      {
        title: '新增專案',
        height: 'auto',
        width: 'auto',
        autoOpen: false
      }
  );
  var obj = this;
  var projectwindow = this.newprojectwindow;
  $(this).click(function() {
    $(projectwindow).dialog('open');
  });
  $(this.newprojectwindow).find('.newprojok').click(function() {
    $(projectwindow).find('input').val('');
    $(projectwindow).dialog('close');
  });
};
