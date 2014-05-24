;

/**
 * @fileoverview This file contains all the js needed for the schedule page
 */


/**
 * This function is used to add a leading zero to hours/minutes if needed. Form
 * example, if you have a time string like "3:9", you can split the hours and
 * minutes and call this function on each digit and in turn it will produce
 * "03:09". So it adds a leading zero if the digit length is 1, if the digit
 * length is already 2 then it will simply return the original number string.
 * @param {string} digits The hour or time string digit.
 * @return {string} "0"+digit when digit's length is 1; otherwise returns
 * digit.
 */
function addZeroForHourMinute(digits) {
  if (digits.length == 1) {
    return '0' + digits;
  } else {
    return digits;
  }
}


/**
 * This function is used to insert a schedule into the database, this is done
 * by getting the user data in the page and sending them over as an ajax call.
 */
function insertSchedule() {
  var period = $('#perioddl :selected').val();
  var type = $('#targettype :selected').val();
  var schedule = 0;
  var time = '';
  var target = {
    year: '',
    type: '',
    project_ids: ''
  };
  if (period == 'weekly') {
    schedule = $('#weekdaydl :selected').val();
    time = addZeroForHourMinute($('#hour :selected').val()) + ':' +
        addZeroForHourMinute($('#minute :selected').val()) + ':00';
  } else if (period == 'daily') {
    time = addZeroForHourMinute($('#hour :selected').val()) + ':' +
        addZeroForHourMinute($('#minute :selected').val()) + ':00';
  } else {
    time = $('#datepick').val() + ' ' + addZeroForHourMinute($(
        '#hour :selected').val()) + ':' + addZeroForHourMinute($(
        '#minute :selected').val()) + ':00';
  }
  if (type == 'yearclass') {
    target.year = $('#proj_year_dl :selected').val();
    target.type = $('#proj_class_dl :selected').val();
  } else {
    target.project_ids = $('#targetids').val();
  }
  alert(period + ' ' + type + ' ' + schedule + ' ' + time + ' ' + target.year +
      ' ' + target.type + ' ' + target.project_ids);
  $.ajax({
    url: '../../schedules/insertschedule',
    type: 'POST',
    async: false,
    data: {
      'period': period,
      'type': type,
      'schedule': schedule,
      'time': time,
      'target': target
    },
    success: function(data) {
      if (data.status == 'success') {
        alert('新增排程成功');
        location.reload(true);
      } else {
        alert('新增排承失敗，原因如下:\n' + data.errorMessage);
      }
    },
    error: function() {
      alert('無法新增排程');
    }
  });
}


/**
 * This function will do an ajax call to the schedules controller's
 * method:getSchedules() to retrieve the information of all the schedules
 * in the system, and then render the schedules to the page. As mentioned
 * in the documentation of the getSchedules method, it will reply active
 * and inactive schedules as two separate array fields, these two fields
 * are then rendered into different divs.
 */
function loadScheduleTable() {
  var schedules = {};
  $.ajax({
    url: '../../schedules/getschedules',
    type: 'GET',
    async: false,
    success: function(data) {
      schedules = data;
    },
    error: function() {
      alert('無法取得排程基本資料，請重新整理此頁面');
    }
  }); //end of ajax
  var activeTableStr = '<tr><th>週期</th><th>時間</th><th>目標</th><th>刪除</th></tr>';
  for (var i = 0; i < schedules.active.length; ++i) {
    var element = schedules.active[i];
    activeTableStr += '<tr><td>' + element.period + '<td>' + element.time +
        '</td><td>' + element.group + '</td><td><button value="' +
        element.schedule_id + '">刪除</button></td></tr>';
  }
  $('#activescheduletable').append(activeTableStr);
  var inactiveTableStr =
      '<tr><th>週期</th><th>時間</th><th>目標</th><th>刪除</th></tr>';
  for (var i = 0; i < schedules.inactive.length; ++i) {
    var element = schedules.inactive[i];
    inactiveTableStr += '<tr><td>' + element.period + '<td>' + element.time +
        '</td><td>' + element.group + '</td><td><button value="' +
        element.schedule_id + '">刪除</button></td></tr>';
  }
  $('#inactivescheduletable').append(inactiveTableStr);
  //delete handling
  var delselector = '#activescheduletable tr td button,' +
      '#inactivescheduletable tr td button';
  $(delselector).click(
      function() {
        var scheduleId = $(this).val();
        var tr = $(this).parent().parent();
        var sch_string = '';
        $(tr).children().each(function() {
          if ($(this).index != 3) {
            sch_string += ' ' + $(this).text();
          }
        });
        if (confirm('確定刪除' + sch_string + ' 的排程內容?')) {
          $.ajax({
            url: '../../' +
                'schedules/deleteschedule',
            type: 'POST',
            data: {
              'schedule_id': scheduleId
            },
            async: false,
            success: function(data) {
              if (data.status == 'success') {
                location.reload(true);
              } else {
                alert('刪除失敗，原因如下:\n' + data.errorMessage);
              }
            },
            error: function() {
              alert('刪除失敗');
            }
          });
        }
      });


}


/**
 * This function is used to show/hide different input elements in the page.
 * Depending on the period selection(id='perioddl') value.
 */
function updateoptions() {
  var optiontype = $('#perioddl').val();
  $('#optioncell span').each(function() {
    $(this).hide();
  });
  if (optiontype == 'weekly') {
    $('#weeklyoptions').show();
  } else if (optiontype == 'one_time') {
    $('#one_timeoptions').show();
  }
}


/**
 * Javascript objects initialization on document ready
 */
$(document).ready(function() {
  /* load all the project ids, years, classes */
  var projectids = [];
  $.ajax({
    url: '../../projects/getids',
    type: 'GET',
    async: false,
    success: function(data) {
      projectids = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  }); //end of ajax
  var years = [];
  var classes = [];
  $.ajax({
    url: '../../' +
        'projects/getvalidprojectyears',
    type: 'GET',
    async: false,
    success: function(data) {
      years = data;
    },
    error: function() {
      alert('無法取得專案年分，請重新整理此頁面');
    }
  }); //end of ajax
  $.ajax({
    url: '../../' +
        'projects/getvalidprojecttypes',
    type: 'GET',
    async: false,
    success: function(data) {
      classes = data;
    },
    error: function() {
      alert('無法取得專案類別，請重新整理此頁面');
    }
  }); //end of ajax

  /* use those info to update ui components*/
  $('#proj_year_dl').append($('<option></option>').attr('value', 'all').text(
      '全部'));
  for (var idx = 0; idx < years.length; idx++) {
    $('#proj_year_dl').append($('<option></option>').attr('value', '' + years[
        idx]).text('' + years[idx]));
  }
  $('#proj_class_dl').append($('<option></option>').attr('value', 'all').text(
      '全部'));
  for (var idx = 0; idx < classes.length; idx++) {
    $('#proj_class_dl').append($('<option></option>').attr('value', classes[
        idx]).text(classes[idx]));
  }
  /* initialize some ui components */
  $('#datepick').datepicker();
  $('#targetids').autocompleteprojectid(projectids);
  /* show the corresponding options */
  updateoptions();
  $('#perioddl').change(function() {
    updateoptions();
  });
  /* load the date/time options */
  var tmphtmlstr = '';
  for (var idx = 1; idx <= 12; idx++) {
    tmphtmlstr += '<option value = ' + idx + '>' + idx + '</option>';
  }
  $('#one_timeoptionsmonthdl').append(tmphtmlstr);

  tmphtmlstr = '';
  for (var idx = 1; idx <= 31; idx++) {
    tmphtmlstr += '<option value = ' + idx + '>' + idx + '</option>';
  }
  $('#one_timeoptionsdaydl').append(tmphtmlstr);

  tmphtmlstr = '';
  for (var idx = 0; idx < 24; idx++) {
    tmphtmlstr += '<option value = ' + idx + '>' + idx + '</option>';
  }
  $('#hour').append(tmphtmlstr);

  tmphtmlstr = '';
  for (var idx = 0; idx < 60; idx++) {
    tmphtmlstr += '<option value = ' + idx + '>' + idx + '</option>';
  }
  $('#minute').append(tmphtmlstr);
  /*update the targetvalue form according to the targettype field selection*/
  $('#targettype').change(function() {
    var targettypeval = $('#targettype :selected').val();
    $('#yearclassform').hide();
    $('#idform').hide();
    if (targettypeval == 'yearclass') {
      $('#yearclassform').show();
    } else {
      $('#idform').show();
    }
  });
  loadScheduleTable();
});
