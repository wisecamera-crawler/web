/**
 * @fileoverview This file contains all the js needed for the import page
 */


/**
 * This function is used to load the #querywindow div with the modification
 * history of the project.
 * @param {Array} history An array of modification history.
 */
function renderHistory(history) {
  $('#querywindow').empty();
  var tablestr =
      '<table class="fancytable"><tr><th class="newsortcontrol">時間' +
      '<div class="arrow-down"></div></th><th class="newsortcontrol">人員' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">年度' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">類別' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">國科會代碼' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">專案名稱' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">主持人' +
      '<div class="sorticon"></div></th><th class="newsortcontrol">平台/網址' +
      '<div class="sorticon"></div></th></tr>';
  for (var i = 0; i < history.length; ++i) {
    tablestr += '<tr><td>' + history[i].timestamp + '</td>';
    tablestr += '<td>' + history[i].user_id + '</td>';
    tablestr += '<td>' + history[i].year + '</td>';
    tablestr += '<td>' + history[i].type + '</td>';
    tablestr += '<td>' + history[i].project_id + '</td>';
    tablestr += '<td>' + history[i].name + '</td>';
    tablestr += '<td>' + history[i].leader + '</td>';
    tablestr += '<td><a href="' + history[i].url + '">' + history[i].platform +
        '</a></td></tr>';
  }
  $('#querywindow').append(tablestr);
  $('#querywindow table .newsortcontrol').click(function() {
    //get the current state of sort
    var column = $(this).index();
    var reverse = false;
    if ($(this).find('.sorticon').length > 0) {
      $('#querywindow table').find('.arrow-up').remove();
      $('#querywindow table').find('.arrow-down').remove();
      $('#querywindow table').find('.sorticon').remove();
      $('#querywindow table .newsortcontrol').append(
          '<div class="sorticon"></div>');
      $(this).find('.sorticon').remove();
      $(this).append('<div class="arrow-down"></div>');
    } else {
      if ($(this).find('.arrow-down').length > 0) {
        reverse = true;
        $(this).find('.arrow-down').remove();
        $(this).append('<div class="arrow-up"></div>');
      } else {
        $(this).find('.arrow-up').remove();
        $(this).append('<div class="arrow-down"></div>');
      }
    }

    var $table = $('#querywindow table');
    var rows = $table.find('tr').get();
    rows.splice(0, 1);
    rows.sort(function(a, b) {
      var aVal = $(a).children('td:nth-child(' + (column + 1) + ')').text();
      var bVal = $(b).children('td:nth-child(' + (column + 1) + ')').text();
      if (!reverse) {
        return aVal.localeCompare(bVal);
      } else {
        return bVal.localeCompare(aVal);
      }
    });
    $.each(rows, function(index, row) {
      $table.children('tbody').append(row);
    });
  });
  //sort the table for the first time, using time as key
  var table = $('#querywindow table');
  var rows = table.find('tr').get();
  rows.splice(0, 1);
  rows.sort(function(a, b) {
    var aVal = $(a).children('td:nth-child(1)').text();
    var bVal = $(b).children('td:nth-child(1)').text();
    return aVal.localeCompare(bVal);
  });
  $('#querywindow').dialog('open');
}


/**
 * This function is used to upload the xml document describing a batch
 * of projects to import. The file is read as a string and sent to the
 * server.
 * @return {boolean} false, this is to prevent the browser from taking
 * default action.
 */
function upload() {
  var reader = new FileReader();
  var file = document.getElementById('file').files[0];
  if (file) {
    reader.readAsText(file);
    reader.onload = fileLoaded;
  }
  return false;
}


/**
 * This function is called when some of the projects in the upload batch file
 * already exists in the system, the user will be prompted with a confirm
 * dialog asking if they want to overwrite the existing projects. If so, then
 * it will send another ajax call to the server and overwrite the projects.
 * @param {string} msg The message containing the project ids that already
 * exist in the system.
 * @param {string} stringData The content of the xml upload batch file.
 */
function confirmOverwrite(msg, stringData) {
  var yes = confirm(msg + '\r\n請問是否確定要覆蓋?');
  if (yes) {
    $.ajax({
      url: '../../projects/uploadbatch',
      type: 'POST',
      data: {
        data: stringData
      },
      async: false,
      success: function(data) {
        if (data.status == 'success') {
          alert('專案匯入成功');
          location.reload(true);
        } else {
          alert(data.errorMessage);
        }
      },
      error: function(xhr, status, error) {
        var err = xhr.responseText;
        console.log(err);
        alert(err);
        alert(error);
      }
    });
  } else {
    alert('取消匯入專案');
  }
}


/**
 * This function is called when the upload file is loaded into the user
 * agent, it will do an ajax call to the server and upload the xml batch
 * file as a string.
 * @param {Event} evt The file loaded event.
 */
function fileLoaded(evt) {
  var stringData = evt.target.result;
  stringData = stringData.replace(/(\r\n|\n|\r|\t)/gm, '');
  $.ajax({
    url: '../../projects/checkuploadbatch',
    type: 'POST',
    data: {
      data: stringData
    },
    async: false,
    success: function(data) {
      if (data.status == 'success') {
        alert('專案匯入成功');
        location.reload(true);
      } else if (data.status == 'confirm') {
        confirmOverwrite(data.confirmMessage, stringData);
      } else {
        alert(data.errorMessage);
      }
    },
    error: function(xhr, status, error) {
      var err = xhr.responseText;
      console.log(err);
      alert(err);
      alert(error);
    }
  });

}


/**
 * This function is used to generate the platform of a project from it's url.
 * @param {string} url The url string of the project.
 * @return {string} The platform of the project as a string.
 */
function findPlatformFromURL(url) {
  if (url.indexOf('code.google.com') >= 0) {
    return 'googlecode';
  } else if (url.indexOf('github.com') >= 0) {
    return 'github';
  } else if (url.indexOf('www.openfoundry.org') >= 0) {
    return 'openfoundry';
  } else if (url.indexOf('sourceforge.net') >= 0) {
    return 'sourceforge';
  }
}


/**
 * This function is used to get the project from the id of the dom element.
 * @param {DomElement} row The dom element of the row.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {Object} returns the project corresponding to the row.
 */
function getprojectfromrowid(row, projects) {
  var id = $(row).attr('id');
  var n = parseInt(id.replace('projectrow', ''));
  return projects[n];
}


/**
 * This function is used to sort the project table with their project ids.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithid(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  return projA.project_id.localeCompare(projB.project_id);
}


/**
 * This function is used to sort the project table with their project platform.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithplatform(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  return projA.platform.localeCompare(projB.platform);
}


/**
 * This function is used to sort the project table with their project year.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithyear(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  if (projA.year > projB.year) return 1;
  else if (projA.year < projB.year) return -1;
  else return 0;
}


/**
 * This function is used to sort the project table with their project name.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithprojectname(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  return projA.name.localeCompare(projB.name);
}


/**
 * This function is used to sort the project table with their project leader.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithprojecthost(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  return projA.leader.localeCompare(projB.leader);

}


/**
 * This function is used to sort the project table with their project type.
 * @param {DomElement} a The dom element of the row a.
 * @param {DomElement} b The dom element of the row b.
 * @param {Array} projects The projects array loaded on document ready.
 * @return {int} Returns 1 if a should be in front of b using this sort;
 * Returns -1 if b should be in front of a; Returns 0 if they should have
 * the same precedence.
 */
function sortwithclass(a, b, projects) {
  var projA = getprojectfromrowid(a, projects);
  var projB = getprojectfromrowid(b, projects);
  return projA.type.localeCompare(projB.type);
}


/**
 * This function is used to sort the project table with a variable sort
 * function.
 * @param {Function} sortfunc The function to sort the table with.
 * @param {Array} projects The projects array loaded on document ready.
 * @param {boolean} reverse Whether to reverse the sorting or not.
 */
function sortwithfunction(sortfunc, projects, reverse) {
  var $table = $('#projecttable');
  var rows = $table.find('tr').get();
  rows.splice(0, 1);
  rows.sort(function(a, b) {
    return sortfunc(a, b, projects);
  });
  if (reverse) rows.reverse();
  $.each(rows, function(index, row) {
    $table.children('tbody').append(row);
  });
}


/**
 * This function is used to sort the project table. It will detect which
 * attributes of the projects are being used as the sort key and also is the
 * sorting in ascending or descending order. It will react to user input and
 * update the css properties along with calling the right sort functions to
 * sort the project table.
 * @param {DomElement} obj The dom element of the sorting control that's been
 * clicked, this function will use it's value to determine what attribute
 * of the projects do we want to sort with.
 * @param {Array} projects The projects array loaded on document ready.
 */
function sortprojects(obj, projects) {
  var clickedvalue = $(obj).attr('value');
  //get the status of current sort
  var prevclicked;
  var prevdown;
  $('.sortcontrol').each(function() {
    var l = $(this).find('.arrow-up').length;
    var m = $(this).find('.arrow-down').length;
    if (l > 0) {
      prevclicked = $(this).attr('value');
      prevdown = false;
    }
    if (m > 0) {
      prevclicked = $(this).attr('value');
      prevdown = true;
    }
  });
  //remove all arrows
  $('.sortcontrol').find('.arrow-up').remove();
  $('.sortcontrol').find('.arrow-down').remove();
  var thistimedown = true;
  var same = false;
  if (prevclicked == clickedvalue) {
    thistimedown = !prevdown;
    same = true;
  }
  if (clickedvalue == 'year') {
    sortwithfunction(sortwithyear, projects, !thistimedown);
  } else if (clickedvalue == 'id') {
    sortwithfunction(sortwithid, projects, !thistimedown);
  } else if (clickedvalue == 'class') {
    sortwithfunction(sortwithclass, projects, !thistimedown);
  } else if (clickedvalue == 'projectname') {
    sortwithfunction(sortwithprojectname, projects, !thistimedown);
  } else if (clickedvalue == 'platform') {
    sortwithfunction(sortwithplatform, projects, !thistimedown);
  } else if (clickedvalue == 'projecthost') {
    sortwithfunction(sortwithprojecthost, projects, !thistimedown);
  }
  if (thistimedown) {
    obj.append('<div class="arrow-down"></div>');
  } else {
    obj.append('<div class="arrow-up"></div>');
  }
  if (!same) {
    obj.find('.sorticon').remove();
    $('.sortcontrol[value="' + prevclicked + '"]').append(
        '<div class="sorticon"></div>');
  }
}


/**
 * This function is used to sort numbers
 * @param {Number} a A number for comparison.
 * @param {Number} b A number for comparison.
 * @return {Number} a-b.
 */
function compareNumbers(a, b) {
  return a - b;
}


/**
 * This function is used to hide the projects that were filtered
 * by the filter controls such as year filter/type filter/id filter...etc.
 * @param {Array} projects The projects array loaded on document ready.
 */
function hideunfittingprojects(projects) {
  var idxset = {};
  var idx = 0;
  var idx2 = 0;
  var arr = [];
  $('#filteryearwindow input:checked').each(function() {
    arr.push($(this).val());
  });
  for (idx = 0; idx < projects.length; idx++) {
    idxset[idx] = true;
  }
  for (idx = 0; idx < projects.length; idx++) {
    var matched = false;
    for (idx2 = 0; idx2 < arr.length; idx2++) {
      if (arr[idx2] == projects[idx].year) {
        matched = true;
        break;
      }
    }
    if (!matched) {
      delete idxset[idx];
    }
  }
  arr = [];
  $('#filterclasswindow input:checked').each(function() {
    arr.push($(this).val());
  });
  for (var key in idxset) {
    var matched = false;
    for (idx = 0; idx < projects.length; idx++) {
      if (arr[idx] == projects[key].type) {
        matched = true;
        break;
      }
    }
    if (!matched) {
      delete idxset[key];
    }
  }
  arr = [];
  //arr = $('#platformdl').val() || [];
  $('#filterplatformwindow input:checked').each(function() {
    arr.push($(this).val());
  });
  for (var key in idxset) {
    var matched = false;
    for (idx = 0; idx < projects.length; idx++) {
      if (arr[idx] == projects[key].platform.replace(' ', '_')) {
        matched = true;
        break;
      }
    }
    if (!matched) {
      delete idxset[key];
    }
  }
  var idfilter = $('#idtb').val();
  if (idfilter.length > 0) {
    for (var key in idxset) {
      if (projects[key].project_id.indexOf(idfilter) < 0) {
        delete idxset[key];
      }
    }
  }
  var projectnamefilter = $('#projectnametb').val();
  if (projectnamefilter.length > 0) {
    for (var key in idxset) {
      if (projects[key].name.indexOf(projectnamefilter) < 0) {
        delete idxset[key];
      }
    }
  }
  for (idx = 0; idx < projects.length; idx++) {
    if (idx in idxset) $('#projectrow' + idx).show();
    else $('#projectrow' + idx).hide();
  }
}


/**
 * This function is used to clear all data input from the modify project
 * window div.
 */
function clearmodifywindow() {
  $('#modprojyear').val('');
  $('#modprojclass').val('');
  $('#modprojid').val('');
  $('#modprojname').val('');
  $('#modprojplatform').val('');
}


/**
 * This function will render a table with id=projecttable, the table
 * will contain all the key information of each project along with buttons
 * used to delete/modify/query modify history of the projects.
 * @param {Array} projects The projects array that's going to be rendered
 * as a table.
 */
function renderprojecttable(projects) {
  if ($('#projwin').length > 0) {
    $('#projwin').remove();
  }
  var htmlstr = '<table id="projecttable" class="fancytable">';
  var tablehead =
      "<tr><th class='sortcontrol' value = 'year'><span>年度" +
      "<div class='sorticon'></div></span></th><th class='sortcontrol'" +
      " value = 'class'>類別<div class='sorticon'></div></th>" +
      "<th class='sortcontrol' value = 'id'>代碼<div class='arrow-down'></div>" +
      "</th><th class='sortcontrol' value = 'projectname'>專案" +
      "<div class='sorticon'></div></th><th class='sortcontrol' " +
      "value = 'projecthost'>主持人<div class='sorticon'></div></th>" +
      "<th class='sortcontrol' value = 'platform'>平台<div " +
      "class='sorticon'></div></th><th>刪除</th><th>修改</th><th>查詢</th></tr>";
  htmlstr += tablehead;

  var idx;
  for (idx = 0; idx < projects.length; idx++) {
    htmlstr += '<tr class="alt" id="projectrow' + idx + '"><td>' + projects[idx]
      .year + '</td><td>' + projects[idx].type + '</td><td>' +
        projects[idx].project_id + '</td><td>' + projects[idx].name +
        '</td><td>' + projects[idx].leader + '</td><td><a href="' +
        projects[idx].url + '">' + projects[idx].platform +
        '</a></td><td><button id="delete' + idx +
        '">刪除</button></td><td><button id="modify' + idx +
        '">修改</button></td><td><button id="query' + idx +
        '">查詢</button></td></tr>';
  }
  htmlstr += '</table>';
  $('#deleteprojectwindow').append(htmlstr);
  for (idx = 0; idx < projects.length; idx++) {
    $('#modify' + idx).click({
      value: idx
    }, function(event) {
      $('#modprojectwindow').dialog('open');
      selectedmodifyidx = event.data.value;
      $('#modprojyear').val(projects[event.data.value].year);
      $('#modprojclass').val(projects[event.data.value].type);
      $('#modprojid').val(projects[event.data.value].project_id);
      $('#modprojname').val(projects[event.data.value].name);
      $('#modprojleader').val(projects[event.data.value].leader);
      $('#modprojplatform').val(projects[event.data.value].url);
    });
    $('#delete' + idx).click({
      value: idx
    }, function(event) {
      if (confirm('確定要刪除' + projects[event.data.value].project_id + '專案?')) {
        $.ajax({
          url: '../../' +
              'projects/deleteproject',
          type: 'POST',
          data: {
            project_id: projects[event.data.value].project_id
          },
          async: false,
          success: function(data) {
            if (data.status == 'success') {
              alert('專案刪除成功');
              location.reload(true);
            } else {
              alert(data.errorMessage);
            }
          },
          error: function(xhr, status, error) {
            var err = xhr.responseText;
            console.log(err);
            alert(err);
            alert(error);
          }
        });
      }
    });
    $('#query' + idx).click({
      value: idx
    }, function(event) {

      $.ajax({
        url: '../../' +
            'projects/getprojectmodificationhistory',
        type: 'POST',
        data: {
          project_id: projects[event.data.value].project_id
        },
        async: false,
        success: function(data) {
          if (data.status == 'success') {
            var history = data.history;
            renderHistory(history);
          } else {
            alert(data.errorMessage);
          }
        },
        error: function(xhr, status, error) {
          var err = xhr.responseText;
          console.log(err);
          alert(err);
          alert(error);
        }
      });
    });

  }

}
var selectedmodifyidx = -1;


/**
 * Javascript initialization on document ready.
 */
$(document).ready(function() {
  $.ajax({
    url: '../../projects/getgenericdata',
    type: 'GET',
    async: false,

    success: function(data) {
      projects = data;
    },
    error: function(xhr, status, error) {
      var err = xhr.responseText;
      alert(err);
      alert(error);
    }
  }); //end of ajax
  renderprojecttable(projects);
  /*load year selections*/
  var legityearsmap = {};
  var legityears = [];
  var idx = 0;
  for (idx = 0; idx < projects.length; idx++) {
    legityearsmap[projects[idx].year] = true;
  }
  for (var key in legityearsmap) {
    if (legityearsmap.hasOwnProperty(key)) {
      legityears.push(key);
    }
  }
  legityears.sort(compareNumbers);
  var yearsdlhtmlstr = '';
  for (idx = 0; idx < legityears.length; idx++) {
    yearsdlhtmlstr += '<input class="filtercheck" type="checkbox" value=' +
        legityears[idx] + ' checked>' + legityears[idx] + '年度</input>';
  }
  $('#filteryearwindow').append(yearsdlhtmlstr);

  /*load class selections*/
  var legitclassmap = {};
  var legitclasses = [];
  for (idx = 0; idx < projects.length; idx++) {
    legitclassmap[projects[idx].type] = true;
  }
  for (var key in legitclassmap) {
    if (legitclassmap.hasOwnProperty(key)) {
      legitclasses.push(key);
    }
  }
  var classdlhtmlstr = '';
  for (idx = 0; idx < legitclasses.length; idx++) {
    classdlhtmlstr += '<input type="checkbox" class="filtercheck" value="' +
        legitclasses[idx] + '" checked>' + legitclasses[idx] + '</input>';
  }
  $('#filterclasswindow').append(classdlhtmlstr);
  /*load platform selections*/
  var legitplatformmap = {};
  var legitplatforms = [];
  for (idx = 0; idx < projects.length; idx++) {
    legitplatformmap[projects[idx].platform] = true;
  }
  for (var key in legitplatformmap) {
    if (legitplatformmap.hasOwnProperty(key)) {
      legitplatforms.push(key);
    }
  }
  var platformdlhtmlstr = '';
  for (idx = 0; idx < legitplatforms.length; idx++) {
    platformdlhtmlstr += '<input type="checkbox" class="filtercheck" value=' +
        legitplatforms[idx].replace(' ', '_') + ' checked>' + legitplatforms[
        idx] + '</input>';
  }
  $('#filterplatformwindow').append(platformdlhtmlstr);
  /*end of filter selections*/
  /*add listeners to filter components*/
  $('.filtercheck').change(function() {
    hideunfittingprojects(projects);
  });
  //$('#idtb').change(function(){hideunfittingprojects(projects);});
  $('#idtb').on('change keyup paste', function() {
    hideunfittingprojects(projects);
  });
  $('#projectnametb').on('change keyup paste', function() {
    hideunfittingprojects(projects);
  });
  /* filter option popup*/
  $('#filteryearwindow').dialog({
    title: '過濾年度',
    height: 'auto',
    width: 'auto',
    autoOpen: false,
    draggable: false,
    modal: true,
    position: {
      my: 'top',
      at: 'bottom',
      of: $('#filteryearbutton')
    },
    buttons: [{
      text: '全選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', true);
        hideunfittingprojects(projects);
      }
    }, {
      text: '全不選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', false);
        hideunfittingprojects(projects);
      }
    }, {
      text: '確定',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });
  $('#filteryearbutton').click(function() {
    $('#filteryearwindow').dialog('open');
  });

  $('#filterclasswindow').dialog({
    title: '過濾類別',
    height: 'auto',
    width: 'auto',
    autoOpen: false,
    draggable: false,
    modal: true,
    position: {
      my: 'top',
      at: 'bottom',
      of: $('#filterclassbutton')
    },
    buttons: [{
      text: '全選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', true);
        hideunfittingprojects(projects);
      }
    }, {
      text: '全不選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', false);
        hideunfittingprojects(projects);
      }
    }, {
      text: '確定',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });
  $('#filterclassbutton').click(function() {
    $('#filterclasswindow').dialog('open');
  });

  $('#filterplatformwindow').dialog({
    title: '過濾平台',
    height: 'auto',
    width: 'auto',
    autoOpen: false,
    draggable: false,
    modal: true,
    position: {
      my: 'top',
      at: 'bottom',
      of: $('#filterplatformbutton')
    },
    buttons: [{
      text: '全選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', true);
        hideunfittingprojects(projects);
      }
    }, {
      text: '全不選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', false);
        hideunfittingprojects(projects);
      }
    }, {
      text: '確定',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });
  $('#filterplatformbutton').click(function() {
    $('#filterplatformwindow').dialog('open');
  });
  /* initialize sorting with id*/
  sortwithfunction(sortwithid, projects, false);
  /* sorting controls */
  $('.sortcontrol').click(function() {
    sortprojects($(this), projects);
  });


  //new/modify project window init

  $('#newprojectwindow').dialog({
    title: '新增專案',
    height: 'auto',
    width: 'auto',
    autoOpen: false
  });
  $('#modprojectwindow').dialog({
    title: '修改專案',
    height: 'auto',
    width: 'auto',
    autoOpen: false
  });
  /* assign handler for new project button*/
  $('#newprojbutton').click(function() {
    $('#newprojectwindow').dialog('open');
    $('#newprojectwindow').find('input').val('');
  });

  $('#newprojok').click(function() {
    //add the project to database
    $.ajax({
      url: '../../projects/setnewproject',
      type: 'POST',
      data: {
        'year': $('#newprojyear').val(),
        'type': $('#newprojclass').val(),
        'project_id': $('#newprojid').val(),
        'name': $('#newprojname').val(),
        'platform': findPlatformFromURL($('#newprojplatform').val()),
        'url': $('#newprojplatform').val(),
        'leader': $('#newprojleader').val()
      },
      async: false,
      success: function(data) {
        if (data.status == 'success') {
          alert('專案資料成功匯入');
          window.location.reload(true);
        } else {
          alert(data.errorMessage);
        }
      },
      error: function(xhr, status, error) {
        var err = xhr.responseText;
        console.log(err);
        alert(err);
        alert(error);
      }
    });
    $('#newprojectwindow').dialog('close');
  });
  $('#newprojcancel').click(function() {
    $('#newprojectwindow').dialog('close');
  });
  /* assign handlers for submit/cancel modify */
  $('#modsubmit').click(function() {
    var modifyData = {};
    var projToMod = projects[selectedmodifyidx];
    var inputyear = $('#modprojyear').val();
    var inputtype = $('#modprojclass').val();
    var inputid = $('#modprojid').val();
    var inputname = $('#modprojname').val();
    var inputurl = $('#modprojplatform').val();
    var inputleader = $('#modprojleader').val();
    modifyData.project_id = inputid;
    if (projToMod.year !== inputyear) {
      modifyData.year = inputyear;
    }
    if (projToMod.type !== inputtype) {
      modifyData.type = inputtype;
    }
    if (projToMod.name !== inputname) {
      modifyData.name = inputname;
    }
    if (projToMod.url !== inputurl) {
      modifyData.url = inputurl;
    }
    if (projToMod.leader !== inputleader) {
      modifyData.leader = inputleader;
    }
    $.ajax({
      url: '../../projects/modifyproject',
      type: 'POST',
      data: {
        'modifydata': modifyData
      },
      async: false,
      success: function(data) {
        if (data.status == 'success') {
          alert('專案修改成功');
          window.location.reload(true);
        } else {
          alert(data.errorMessage);
        }
      },
      error: function(xhr, status, error) {
        var err = xhr.responseText;
        console.log(err);
        alert(err);
        alert(error);
      }
    });
  });
  $('#modcancel').click(function() {
    clearmodifywindow();
    selectedmodifyidx = -1;
    $('#modprojectwindow').dialog('close');
  });
  $('#confirmwindow').dialog({
    title: '專案修改確認',
    height: 'auto',
    width: 'auto',
    autoOpen: false,
    draggable: false,
    modal: true,
    buttons: [{
      text: '確認',
      click: function() {
        $(this).find('.filtercheck').prop('checked', true);
        hideunfittingprojects(projects);
      }
    }, {
      text: '取消',
      click: function() {
        $(this).find('.filtercheck').prop('checked', false);
        hideunfittingprojects(projects);
      }
    }]
  });
});
$('#querywindow').dialog({
  title: '修改紀錄查詢',
  height: 'auto',
  width: 'auto',
  autoOpen: false,
  draggable: false,
  modal: true
});
