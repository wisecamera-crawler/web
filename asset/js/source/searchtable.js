/**
 * @fileoverview This file contains all the js needed for the searchtable page
 */


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
 * This function is used to sort the project table with their project types.
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


/*plotting functions */
/**
 * This function is used to plot a trend graph using the arguments as input.
 * The function will sort the data with date, and then show the last n results
 * depending on the maxshows argument.
 * @param {string} id The id of the div for the graph to be rendered to.
 * @param {string} xlabel The label displayed on the X-axis of the graph
 * @param {string} ylabel The label displayed on the Y-axis of the graph
 * @param {string} data The data array that is going to be rendered.
 * @param {string} maxshow a string integer, only the last maxshow datas
 * @param {string} chartTitle the title of the chart.
 * will be plotted on the graph.
 */
function plottrendgraph(id, xlabel, ylabel, data, maxshow, chartTitle) {
  if (data == undefined) {
    alert('此專案尚無資料');
    return;
  } else if (data.length == 0) {
    alert('此專案尚無資料');
    return;

  }
  var min = 1.7976931348623157E+10308;
  var max = -1.7976931348623157E+10308;
  var str = '';
  for (var i = 0; i < data.length; ++i) {
    data[i][1] = parseInt(data[i][1]);
  }
  //sort with date, ascending
  data.sort(function(a, b) {
    var dateA = a[0];
    var dateB = b[0];
    return dateA.localeCompare(dateB);
  });
  if (maxshow != 'all') {
    if (data.length > parseInt(maxshow)) {
      data = data.splice(0, parseInt(maxshow));
    }
  }


  var plot1 = $.jqplot(id, [data], {
    title: chartTitle,
    axes: {
      xaxis: {
        renderer: $.jqplot.DateAxisRenderer,
        tickOptions: {
          formatString: '%b %d'
        },
        label: xlabel
      },
      yaxis: {
        tickRenderer: $.jqplot.AxisTickRenderer,
        tickOptions: {
          show: true
        },
        label: ylabel
      }
    },

    series: [{
      pointLabels: {
        show: true,
        edgeTolerance: -50
      }
    }],
    animate: true,
    animateReplot: true
  });
}
var projectStatus = {};


/**
 * This function is used to load the status viewer graphs data("projectStatus")
 * It does this by doing an ajax call with the project_id contained in the post
 * body, the server side will get the data and send it back to this function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the status data from the server.
 */
function loadStatusViewer(prj) {
  $.ajax({
    url: '../../' +
        'projects/getstatusgraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      projectStatus = data;
    },
    error: function(xhr, status, error) {
      var err = xhr.responseText;
      alert(err);
      alert(error);
    }
  });
  $('#statusviewer .statusviewtype').off('change');
  $('#statusviewer .statusviewtype').change(function() {
    plotStatusViewer();
  });

  /*add listeners to the checkboxes*/
  $('#statusviewer .showplot').off('click');
  $('#statusviewer .showplot').click(function() {
    plotStatusViewer();
  });
  $('#statusviewer').dialog('option', 'title', prj.project_id + ' 狀態歷史資料');
  $('#statusviewer').dialog('open');
  plotStatusViewer();
}


/**
 * This function is used to extract data from the projectsStatus object and
 * return an array that can be used to plot the graph, to be more precise, an
 * array of (date,count) pairs.
 * @param {string} propertyName The viewType of the status viewer, i.e.
 * wiki, vcs, issue tracker, download.
 * @param {string} subPropertyName The line to be rendered, i.e.
 * all_success, success_update, no_change...etc.
 * @return {Array} Returns an array of (date,count) pairs.
 */
function extractData(propertyName, subPropertyName) {
  var output = [];
  for (var key in projectStatus) {
    var arr = [key, parseInt(
        projectStatus[key][propertyName][subPropertyName])];
    output.push(arr);
  }
  return output;
}


/**
 * This function is used to gather data from the status viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotStatusViewer() {
  if (projectStatus.length == 0) {
    alert('此專案尚無資料');
    return;
  }
  var viewType = $('#statusviewer .statusviewtype :selected').val();
  var allSuccessLine = fillZeroDates(extractData(viewType, 'all_success'));
  var successUpdateLine = fillZeroDates(
      extractData(viewType, 'success_update'));
  var noChangeLine = fillZeroDates(extractData(viewType, 'no_change'));
  var allFailLine = fillZeroDates(extractData(viewType, 'all_fail'));
  var cannotGetDataLine = fillZeroDates(
      extractData(viewType, 'cannot_get_data'));
  var cannotResolveLine = fillZeroDates(
      extractData(viewType, 'can_not_resolve'));
  var noProxyLine = fillZeroDates(
      extractData(viewType, 'no_proxy'));
  var proxyErrorLine = fillZeroDates(
      extractData(viewType, 'proxy_error'));
  var dateSpan = Math.floor((Date.parse(allSuccessLine[allSuccessLine.length -
      1][0]) - Date.parse(allSuccessLine[0][0])) / 86400000);
  var width = parseInt($('#statusgraph').css('width'));
  var pixelsPerTick = 50;
  var tickInterval = 1; //In days

  if (dateSpan > 0) {
    tickInterval = Math.ceil(dateSpan / (width / 50));
  }
  $('#statusgraph').empty();
  var plot1 = $.jqplot('statusgraph', [allSuccessLine, successUpdateLine,
    noChangeLine, allFailLine, cannotGetDataLine, cannotResolveLine,
    noProxyLine, proxyErrorLine
  ], {
    title: '狀態圖表',
    axes: {
      xaxis: {
        renderer: $.jqplot.DateAxisRenderer,
        tickRenderer: $.jqplot.CanvasAxisTickRenderer,
        tickOptions: {
          formatString: '%b %d'
        },
        tickInterval: tickInterval + ' day'
      },
      yaxis: {
        min: 0
      }
    },
    highlighter: {
      show: true
    },
    animate: true,
    animateReplot: true,
    legend: {
      show: true,
      location: 'e',
      placement: 'inside',
      labels: ['成功', '資料改變且匯入成功', '資料無改變', '失敗', '無法取得資料頁面',
        '解析失敗', '無Proxy可用', 'Proxy錯誤'
      ]
    }
  });

  plot1.series[0].show = $('#statusviewer .showplot[value="all_success"').prop(
      'checked');
  plot1.series[1].show = $('#statusviewer .showplot[value="success_update"')
      .prop('checked');
  plot1.series[2].show = $('#statusviewer .showplot[value="no_change"').prop(
      'checked');
  plot1.series[3].show = $('#statusviewer .showplot[value="fail"').prop(
      'checked');
  plot1.series[4].show = $('#statusviewer .showplot[value="cannot_get_data"')
      .prop('checked');
  plot1.series[5].show = $('#statusviewer .showplot[value="can_not_resolve"')
    .prop('checked');
  plot1.series[6].show = $('#statusviewer .showplot[value="no_proxy"')
    .prop('checked');
  plot1.series[7].show = $('#statusviewer .showplot[value="proxy_error"')
    .prop('checked');
  plot1.replot();
}

var crawlstatus = {};


/**
 * This function is used to load the crawler viewer graph's data("crawlstatus")
 * It does this by doing an ajax call with the project_id contained in the post
 * body, the server side will get the data and send it back to this function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the crawler data from the server.
 */
function loadcrawlerviewer(prj) {
  /* generate the all option array */
  $.ajax({
    url: '../../projects/getproxygraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      crawlstatus = data;
    },
    error: function(xhr, status, error) {
      var err = xhr.responseText;
      alert(err);
      alert(error);
    }
  });

  var all = [];
  for (var key in crawlstatus) {
    all = all.concat(crawlstatus[key]);
  }
  // sort all the arrays with date
  all.sort(function(a, b) {
    var dateA = new Date(a.day);
    var dateB = new Date(b.day);
    if (dateA.getTime() < dateB.getTime()) return -1;
    else if (dateA.getTime() > dateB.getTime()) return 1;
    else return 0;
  });
  // merge elements with the same date
  var organized = [];
  for (var i = 0; i < all.length; ++i) {
    if (organized.hasOwnProperty(all[i].day)) {
      organized[all[i].day].all_success += all[i].all_success;
      organized[all[i].day].success_update += all[i].success_update;
      organized[all[i].day].no_change += all[i].no_change;
      organized[all[i].day].fail += all[i].fail;
      organized[all[i].day].day = all[i].day;
    } else {
      organized[all[i].day] = {};
      organized[all[i].day].all_success = all[i].all_success;
      organized[all[i].day].success_update = all[i].success_update;
      organized[all[i].day].no_change = all[i].no_change;
      organized[all[i].day].fail = all[i].fail;
      organized[all[i].day].day = all[i].day;
    }
  }
  //put them into crawlstatus
  var allArray = [];
  for (var key in organized) {
    allArray = allArray.concat(organized[key]);
  }
  crawlstatus['all'] = allArray;
  /* end of generating all option */
  /* load the crawler options to selection */
  $('#crawlerviewer .crawlerselect').empty();
  for (var key in crawlstatus) {
    if (key == 'all') {
      $('#crawlerviewer .crawlerselect').append($(
          '<option value="all">全部</option>'));
    } else {
      $('#crawlerviewer .crawlerselect').append($('<option></option>').attr(
          'value', key).text(key));
    }
  }
  $('#crawlerviewer .crawlerselect').off('change');
  $('#crawlerviewer .crawlerselect').change(function() {
    plotcrawlerviewer();
  });
  $('#crawlerviewer .showplot').off('click');
  $('#crawlerviewer .showplot').click(function() {
    plotcrawlerviewer();
  });
  /* end of loading crawler options */
  $('#crawlerviewer').dialog('option', 'title', prj.project_id +
      ' crawler歷史資料');
  $('#crawlerviewer').dialog('open');
  plotcrawlerviewer();

}


/**
 * This function is used to extract data from the crawlstatus object and
 * return an array that can be used to plot the graph, to be more precise, an
 * array of (date,count) pairs.
 * @param {Array} status The raw crawlstatus data.
 * @param {string} lineName The line to be rendered, i.e.
 * all_success, success_update, no_change...etc.
 * @return {Array} Returns an array of (date,count) pairs.
 */
function extractWithPropertyName(status, lineName) {
  var output = [];
  for (var i = 0; i < status.length; ++i) {
    var arr = [status[i].day, status[i][lineName]];
    output.push(arr);
  }
  return output;
}


/**
 * This function is used to add entries where no crawling was done to the
 * project, the raw data you get from crawlstatus do not record dates
 * that no schedule was run, so this function inserts dates will zero
 * counts.
 * @param {Array} data The raw data.
 * @return {Array} Returns an array of (date,count) pairs with dates that no
 * crawling was done inserted.
 */
function fillZeroDates(data) {
  if (data === undefined) {
    return data;
  } else if (data.length == 0) {
    return data;
  }
  var d = new Date(data[0][0]);
  var output = [];
  output.push(data[0]);
  for (var i = 1; i < data.length; ++i) {
    var start = new Date(data[i - 1][0]);
    start.setDate(start.getDate() + 1);
    var end = new Date(data[i][0]);
    for (var d = start; d < end; d.setDate(d.getDate() + 1)) {
      var day = new Date(d);
      day.setHours(0, 0, 0, 0);
      var daystr = day.getFullYear() + '-' + (day.getMonth() + 1) + '-' +
          day.getDate();
      var arr = [daystr, 0];
      output.push(arr);

    }
    output.push(data[i]);

  }
  return output;
}


/**
 * This function is used to gather data from the crawler viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotcrawlerviewer() {
  $('#crawlergraph').empty();
  if (crawlstatus.length == 0) {
    alert('此專案尚無資料');
    return;
  }
  var selected_crawler = $('#crawlerviewer .crawlerselect').val();
  var all_success_line = fillZeroDates(extractWithPropertyName(crawlstatus[
      selected_crawler], 'all_success'));
  var success_update_line = fillZeroDates(extractWithPropertyName(crawlstatus[
      selected_crawler], 'success_update'));
  var no_change_line = fillZeroDates(extractWithPropertyName(crawlstatus[
      selected_crawler], 'no_change'));
  var fail_line = fillZeroDates(extractWithPropertyName(crawlstatus[
      selected_crawler], 'fail'));
  // calculate how many days for one tick
  var dateSpan = Math.floor((Date.parse(all_success_line[
      all_success_line.length - 1][0]) - Date.parse(all_success_line[0][0])) /
      86400000);
  var width = parseInt($('#crawlergraph').css('width'));
  var pixelsPerTick = 50;
  var tickInterval = 1; //In days

  if (dateSpan > 0) {
    tickInterval = Math.ceil(dateSpan / (width / 50));
  }
  var plot1 = $.jqplot('crawlergraph', [all_success_line, success_update_line,
    no_change_line, fail_line
  ], {
    title: 'Proxy status',
    axes: {
      xaxis: {
        renderer: $.jqplot.DateAxisRenderer,
        tickOptions: {
          formatString: '%b %d'
        },
        tickInterval: tickInterval + ' day'
      },
      yaxis: {
        min: 0
      }
    },
    highlighter: {
      show: true
    },
    legend: {
      show: true,
      location: 'e',
      placement: 'inside',
      labels: ['成功', '資料改變且匯入成功', '資料無改變', '失敗']
    },
    animate: true,
    animateReplot: true
  });
  /*add listeners to the checkboxes*/
  plot1.series[0].show = $('#crawlerviewer .showplot[value="all_success"').prop(
      'checked');
  plot1.series[1].show = $('#crawlerviewer .showplot[value="success_update"')
    .prop('checked');
  plot1.series[2].show = $('#crawlerviewer .showplot[value="no_change"').prop(
      'checked');
  plot1.series[3].show = $('#crawlerviewer .showplot[value="fail"').prop(
      'checked');
  plot1.replot();
}
var downloaddata = [];
var downloadsinglefiledata = [];
var organizeddownloadsinglefiledata = {};


/**
 * This function is used to load the download viewer graphs data :
 * downloaddata, downloadsinglefiledata, orgainzeddownloadsinglefiledata.
 * It does this by doing ajax calls with the project_id contained in the
 * post body, the server side will get the data and send it back to this
 * function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the download data from the server.
 */
function loaddownloadviewer(prj) {
  //ajax and find the historical data of the project
  $.ajax({
    url: '../../' +
        'projects/getdownloadgraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      downloaddata = data;
    },
    error: function(xhr, status, error) {
      var err = xhr.responseText;
      alert(err);
    }
  });
  $.ajax({
    url: '../../' +
        'projects/getdownloadsinglefilegraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      downloadsinglefiledata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  });


  var legitnames = {};
  for (var i = 0; i < downloadsinglefiledata.length; ++i) {
    legitnames[downloadsinglefiledata[i].name] = true;
  }
  //for each thread name, set an array and assign to organized
  for (var t in legitnames) {
    var narr = [];
    for (var i = 0; i < downloadsinglefiledata.length; ++i) {
      if (downloadsinglefiledata[i].name == t) {
        narr.push(downloadsinglefiledata[i]);
      }

    }
    organizeddownloadsinglefiledata[t] = narr;
  }
  /* load each thread title to threadnamelist*/
  $('#downloadviewer').find('.threadnamelist').empty();
  var threadnameoptshtml = '';
  for (var key in legitnames) {
    threadnameoptshtml += '<option value="' + key + '">' + key + '</option>';
  }
  $('#downloadviewer').find('.threadnamelist').append(threadnameoptshtml);
  //empty the div and render
  $('#downloadviewer').dialog('option', 'title', prj.project_id +
      'download歷史資料');
  $('#downloadviewer').dialog('open');
  plotdownloadviewer();
  $('#downloadviewer').find('.displaytype').off('change');
  $('#downloadviewer').find('.displaytype').change(function() {
    //$('#downloadviewer').find('.threadnamelist').off('change');

    var dt = $('#downloadviewer').find('.displaytype :selected').val();
    if (dt.indexOf('total') < 0) {
      //single options
      $('#downloadviewer').find('.singlethreadpanel').show();
    } else {
      $('#downloadviewer').find('.singlethreadpanel').hide();
    }
    plotdownloadviewer();
  });
  $('#downloadviewer').find('.threadnamelist').off('change');
  $('#downloadviewer').find('.threadnamelist').change(function() {
    plotdownloadviewer();
  });

  $('#downloadviewer').find('.numdatas').off('change');
  $('#downloadviewer').find('.numdatas').change(function() {
    plotdownloadviewer();
  });


}


/**
 * This function is used to gather data from the download viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotdownloadviewer() {
  $('#downloadgraph').empty();
  //check the status of the window to determine which item to plot
  var dt = $('#downloadviewer').find('.displaytype :selected').val();
  if (dt == 'totalfiles') {
    var line = [];
    for (var i = 0; i < downloaddata.length; ++i) {
      line.push([downloaddata[i].timestamp, downloaddata[i].totalfiles]);
    }
    plottrendgraph('downloadgraph', '日期', '總檔案數', line, $(
        '#downloadviewer .numdatas :selected').val(), 'Download 圖表');
  } else if (dt == 'totaldownloads') {
    var line = [];
    for (var i = 0; i < downloaddata.length; ++i) {
      line.push([downloaddata[i].timestamp, downloaddata[i].totaldownloads]);
    }
    plottrendgraph('downloadgraph', '日期', '總下載次數', line, $(
        '#downloadviewer .numdatas :selected').val(), 'Download 圖表');

  } else if (dt == 'singledownloads') {
    var threadname = $('#downloadviewer')
        .find('.threadnamelist :selected').val();
    var line = [];
    for (var i = 0; i < organizeddownloadsinglefiledata[threadname].length;
        ++i) {
      line.push([organizeddownloadsinglefiledata[threadname][i].timestamp,
        organizeddownloadsinglefiledata[threadname][i].count
      ]);
    }
    plottrendgraph('downloadgraph', '日期', '單一檔案下載次數', line, $(
        '#downloadviewer .numdatas :selected').val(), 'Download 圖表');
  }

}
var issuetrackerdata = [];


/**
 * This function is used to load the issue tracker viewer graphs data :
 * issuetrackerdata.
 * It does this by doing an ajax call with the project_id contained in the
 * post body, the server side will get the data and send it back to this
 * function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the issue tracker data from the server.
 */
function loadissuetrackerviewer(prj) {
  $.ajax({
    url: '../../' +
        'projects/getissuetrackergraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      issuetrackerdata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  });
  $('#issuetrackerviewer').dialog('option', 'title', prj.project_id +
      ' issue tracker歷史資料');
  $('#issuetrackerviewer').dialog('open');
  plotissuetrackerviewer();
  $('#issuetrackerviewer').find('.displaytype').off('change');
  $('#issuetrackerviewer').find('.displaytype').change(function() {
    plotissuetrackerviewer();
  });

  $('#issuetrackerviewer').find('.numdatas').off('change');
  $('#issuetrackerviewer').find('.numdatas').change(function() {
    plotissuetrackerviewer();
  });

}


/**
 * This function is used to gather data from the issue tracker viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotissuetrackerviewer() {
  $('#issuetrackergraph').empty();
  //check the status of the window to determine which item to plot
  var dt = $('#issuetrackerviewer').find('.displaytype :selected').val();
  if (dt == 'totalthreads') {
    var line = [];
    for (var i = 0; i < issuetrackerdata.length; ++i) {
      line.push([issuetrackerdata[i].timestamp, issuetrackerdata[i].topic]);
    }
    plottrendgraph('issuetrackergraph', '日期', '主題總數', line, $(
        '#issuetrackerviewer .numdatas :selected').val(), 'Issue Tracker 圖表');

  } else if (dt == 'totalreplies') {
    var line = [];
    for (var i = 0; i < issuetrackerdata.length; ++i) {
      line.push([issuetrackerdata[i].timestamp, issuetrackerdata[i].article]);
    }
    plottrendgraph('issuetrackergraph', '日期', '回應總數', line, $(
        '#issuetrackerviewer .numdatas :selected').val(), 'Issue Tracker 圖表');
  } else if (dt == 'totalaccounts') {
    var line = [];
    for (var i = 0; i < issuetrackerdata.length; ++i) {
      line.push([issuetrackerdata[i].timestamp, issuetrackerdata[i].account]);
    }
    plottrendgraph('issuetrackergraph', '日期', '不同帳號總數', line, $(
        '#issuetrackerviewer .numdatas :selected').val(), 'Issue Tracker 圖表');

  }
}
var vcsdata = [];
var vcscommiterdata = [];
var organizedvcscommiterdata = {};


/**
 * This function is used to load the vcs viewer graphs data :
 * vcsdata, vcscommiterdata, organizedvcscommiterdata.
 * It does this by doing ajax calls with the project_id contained in the
 * post body, the server side will get the data and send it back to this
 * function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the vcs data from the server.
 */
function loadvcsviewer(prj) {
  //ajax and find the historical data of the project
  $.ajax({
    url: '../../projects/getvcsgraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      vcsdata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  }); //end of ajax

  /* parse vcs commiter data and put into organized form*/
  $.ajax({
    url: '../../' +
        'projects/getvcscommitergraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,
    success: function(data) {
      vcscommiterdata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  });

  var legittimestamps = {};
  for (var i = 0; i < vcscommiterdata.length; ++i) {
    legittimestamps[vcscommiterdata[i].timestamp] = true;
  }
  //for each timestamp, put the data in that category
  for (var t in legittimestamps) {
    var narr = [];
    for (var i = 0; i < vcscommiterdata.length; ++i) {
      if (vcscommiterdata[i].timestamp == t) {
        narr.push(vcscommiterdata[i]);
      }
    }
    organizedvcscommiterdata[t] = narr;
  }
  /* load each timestamp to datelist*/
  $('#vcsviewer').find('.datelist').empty();
  var datelistoptshtml = '';
  for (var key in legittimestamps) {
    datelistoptshtml += '<option value="' + key + '">' + key + '</option>';
  }
  $('#vcsviewer').find('.datelist').append(datelistoptshtml);
  //empty the div and render
  $('#vcsviewer').dialog('option', 'title', prj.project_id + ' vcs歷史資料');
  $('#vcsviewer').dialog('open');
  plotvcsviewer();
  $('#vcsviewer').find('.displaytype').off('change');
  $('#vcsviewer').find('.displaytype').change(function() {
    var dt = $('#vcsviewer').find('.displaytype :selected').val();
    if (dt.indexOf('total') < 0) {
      //contribution options(timestamp)
      $('#vcsviewer').find('.contributionpanel').show();
    } else {
      $('#vcsviewer').find('.contributionpanel').hide();
    }
    plotvcsviewer();
  });
  $('#vcsviewer').find('.datelist').off('change');
  $('#vcsviewer').find('.datelist').change(function() {
    plotvcsviewer();
  });
  $('#vcsviewer').find('.numdatas').off('change');
  $('#vcsviewer').find('.numdatas').change(function() {
    plotvcsviewer();
  });
}


/**
 * This function is used to gather data from the vcs viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotvcsviewer() {
  $('#vcsgraph').empty();
  //check the status of the window to determine which item to plot
  var dt = $('#vcsviewer').find('.displaytype :selected').val();
  if (dt == 'totalcommits') {
    var line = [];
    for (var i = 0; i < vcsdata.length; ++i) {
      line.push([vcsdata[i].timestamp, vcsdata[i].commit]);
    }
    plottrendgraph('vcsgraph', '日期', '總commit數', line, $(
        '#vcsviewer .numdatas :selected').val(), 'VCS 圖表');

  } else if (dt == 'totallines') {
    var line = [];
    for (var i = 0; i < vcsdata.length; ++i) {
      line.push([vcsdata[i].timestamp, vcsdata[i].line]);
    }
    plottrendgraph('vcsgraph', '日期', '總行數', line, $(
        '#vcsviewer .numdatas :selected').val(), 'VCS 圖表');
  } else if (dt == 'totalfiles') {
    var line = [];
    for (var i = 0; i < vcsdata.length; ++i) {
      line.push([vcsdata[i].timestamp, vcsdata[i].file]);
    }
    plottrendgraph('vcsgraph', '日期', '總檔案數', line, $(
        '#vcsviewer .numdatas :selected').val(), 'VCS 圖表');

  } else if (dt == 'totalfilesize') {
    var line = [];
    for (var i = 0; i < vcsdata.length; ++i) {
      line.push([vcsdata[i].timestamp, vcsdata[i].size]);
    }
    plottrendgraph('vcsgraph', '日期', '總檔案大小(KB)', line, $(
        '#vcsviewer .numdatas :selected').val(), 'VCS 圖表');
  } else if (dt == 'totalusers') {
    var line = [];
    for (var i = 0; i < vcsdata.length; ++i) {
      line.push([vcsdata[i].timestamp, vcsdata[i].user]);
    }
    plottrendgraph('vcsgraph', '日期', '總貢獻者數', line, $(
        '#vcsviewer .numdatas :selected').val(), 'VCS 圖表');
  } else if (dt == 'usercontribution') {
    var displaytimestamp = $('#vcsviewer').find('.datelist :selected').val();
    var addline = [];
    var modifyline = [];
    var deleteline = [];
    var ticks = [];
    for (var i = 0; i < organizedvcscommiterdata[displaytimestamp].length;
        ++i) {
      ticks[i] = organizedvcscommiterdata[displaytimestamp][i].commiter;
      addline.push([organizedvcscommiterdata[displaytimestamp][i]['new'],
        organizedvcscommiterdata[displaytimestamp][i].commiter
      ]);
      modifyline.push([organizedvcscommiterdata[displaytimestamp][i].modify,
        organizedvcscommiterdata[displaytimestamp][i].commiter
      ]);
      deleteline.push([organizedvcscommiterdata[displaytimestamp][i]['delete'],
        organizedvcscommiterdata[displaytimestamp][i].commiter
      ]);
    }

    var plot1 = $.jqplot('vcsgraph', [addline, modifyline, deleteline], {
      seriesDefaults: {
        renderer: $.jqplot.BarRenderer,
        pointLabels: {
          show: true,
          location: 'e',
          edgeTolerance: -15
        },
        shadowAngle: 135,
        rendererOptions: {
          barDirection: 'horizontal'
        }
      },
      series: [{
        label: 'Add'
      }, {
        label: 'Modify'
      }, {
        label: 'Delete'
      }],
      axes: {
        yaxis: {
          renderer: $.jqplot.CategoryAxisRenderer
        }
      },
      legend: {
        show: true,
        placement: 'inside',
        rendererOptions: {
          numberRows: 1
        },
        location: 'ne',
        marginTop: '15px'
      }
    });
  }


}
var wikidata = [];
var wikisinglethreaddata = [];
var organizedwikisinglethreaddata = {};


/**
 * This function is used to load the wiki viewer graphs data :
 * wikidata, wikisinglethreaddata, organizedwikisinglethreaddata.
 * It does this by doing ajax calls with the project_id contained in the
 * post body, the server side will get the data and send it back to this
 * function.
 * @param {Object} prj A project object, the ajax call will use the
 * field "project_id" of prj to get the vcs data from the server.
 */
function loadwikiviewer(prj) {
  //ajax and find the historical data of the project
  $.ajax({
    url: '../../projects/getwikigraphdata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,

    success: function(data) {
      wikidata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  });
  $.ajax({
    url: '../../' +
        'projects/getwikigraphsinglethreaddata',
    type: 'POST',
    data: {
      'project_id': prj.project_id
    },
    async: false,
    success: function(data) {
      wikisinglethreaddata = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  });

  var legittitles = {};
  for (var i = 0; i < wikisinglethreaddata.length; ++i) {
    legittitles[wikisinglethreaddata[i].title] = true;
  }
  //for each thread title, set an array and assign to organized
  for (var t in legittitles) {
    var narr = [];
    for (var i = 0; i < wikisinglethreaddata.length; ++i) {
      if (wikisinglethreaddata[i].title == t) {
        narr.push(wikisinglethreaddata[i]);
      }

    }
    organizedwikisinglethreaddata[t] = narr;
  }
  /* load each thread title to threadnamelist*/
  $('#wikiviewer').find('.threadnamelist').empty();
  var threadnameoptshtml = '';
  for (var key in legittitles) {
    threadnameoptshtml += '<option value="' + key + '">' + key + '</option>';
  }
  $('#wikiviewer').find('.threadnamelist').append(threadnameoptshtml);
  //empty div and render
  $('#wikiviewer').dialog('option', 'title', prj.project_id + ' wiki歷史資料');
  $('#wikiviewer').dialog('open');
  plotwikiviewer();
  $('#wikiviewer').find('.displaytype').off('change');
  $('#wikiviewer').find('.displaytype').change(function() {
    var dt = $('#wikiviewer').find('.displaytype :selected').val();
    if (dt.indexOf('total') < 0) {
      //single options
      $('#wikiviewer').find('.singlethreadpanel').show();
    } else {
      $('#wikiviewer').find('.singlethreadpanel').hide();
    }
    plotwikiviewer();
  });
  $('#wikiviewer').find('.threadnamelist').off('change');
  $('#wikiviewer').find('.threadnamelist').change(function() {
    plotwikiviewer();
  });
  $('#wikiviewer').find('.numdatas').off('change');
  $('#wikiviewer').find('.numdatas').change(function() {
    plotwikiviewer();
  });
}


/**
 * This function is used to gather data from the wiki viewer window
 * and plot the graph accordingly. It will also do x-axis scaling depending
 * on the earliest and latest date of the data.
 */
function plotwikiviewer() {
  $('#wikigraph').empty();
  //check the status of the window to determine which item to plot
  var dt = $('#wikiviewer').find('.displaytype :selected').val();
  if (dt == 'totalthreads') {
    var line = [];
    for (var i = 0; i < wikidata.length; ++i) {
      line.push([wikidata[i].timestamp, wikidata[i].pages]);
    }
    plottrendgraph('wikigraph', '日期', '總篇數', line, $(
        '#wikiviewer .numdatas :selected').val(), 'Wiki 圖表');
  } else if (dt == 'totallines') {
    var line = [];
    for (var i = 0; i < wikidata.length; ++i) {
      line.push([wikidata[i].timestamp, wikidata[i].line]);
    }
    plottrendgraph('wikigraph', '日期', '總行數', line, $(
        '#wikiviewer .numdatas :selected').val(), 'Wiki 圖表');

  } else if (dt == 'totalupdates') {
    var line = [];
    for (var i = 0; i < wikidata.length; ++i) {
      line.push([wikidata[i].timestamp, wikidata[i].update]);
    }
    plottrendgraph('wikigraph', '日期', '總更新數', line, $(
        '#wikiviewer .numdatas :selected').val(), 'Wiki 圖表');
  } else if (dt == 'singlethreadlines') {
    var threadname = $('#wikiviewer').find('.threadnamelist :selected').val();
    var line = [];
    for (var i = 0; i < organizedwikisinglethreaddata[threadname].length; ++i) {
      line.push([organizedwikisinglethreaddata[threadname][i].timestamp,
        organizedwikisinglethreaddata[threadname][i].line
      ]);
    }
    plottrendgraph('wikigraph', '日期', '單一篇行數', line, $(
        '#wikiviewer .numdatas :selected').val(), 'Wiki 圖表');
  } else if (dt == 'singlethreadupdates') {
    var threadname = $('#wikiviewer').find('.threadnamelist :selected').val();
    var line = [];
    for (var i = 0; i < organizedwikisinglethreaddata[threadname].length; ++i) {
      line.push([organizedwikisinglethreaddata[threadname][i].timestamp,
        organizedwikisinglethreaddata[threadname][i].update
      ]);
    }
    plottrendgraph('wikigraph', '日期', '單一篇更新數', line, $(
        '#wikiviewer .numdatas :selected').val(), 'Wiki 圖表');
  }
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
 * This function is used to sort numbers
 * @param {Number} a A number for comparison.
 * @param {Number} b A number for comparison.
 * @return {Number} a-b.
 */
function compareNumbers(a, b) {
  return a - b;
}


/**
 * Initialization process required when document ready. Will do things like get
 * the projects info via ajax. Generate the filter options from the projects
 * information. Event handling. Render the project table. Initialize all the
 * dialog windows
 */
$(document).ready(function() {
  /*load projects*/
  var projects = [];
  $.ajax({
    url: '../../projects/getgenericdata',
    type: 'GET',
    async: false,
    success: function(data) {
      projects = data;
    },
    error: function() {
      alert('無法取得專案基本資料，請重新整理此頁面');
    }
  }); //end of ajax
  /*initialize dialogs*/
  $('#wikiviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
  $('#vcsviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
  $('#issuetrackerviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
  $('#downloadviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
  $('#crawlerviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
  $('#statusviewer').dialog({
    autoOpen: false,
    height: 'auto',
    width: 'auto'
  });
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
    classdlhtmlstr += '<input type="checkbox" class="filtercheck" value=' +
        legitclasses[idx] + ' checked>' + legitclasses[idx] + '</input>';
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
  /*render all projects*/
  var projectshtmlstr = '';
  for (idx = 0; idx < projects.length; idx++) {
    projectshtmlstr += '<tr class="alt" id="projectrow' + idx + '">';
    projectshtmlstr += '<td>' + projects[idx].year + '</td><td>' + projects[
        idx].type + '</td><td>' + projects[idx].project_id + '</td><td>' +
        projects[idx].name + '</td><td>' + projects[idx].leader +
        '</td><td><a href="' + projects[idx].url + '">' +
        projects[idx].platform + '</a></td>';
    /* for wiki */
    projectshtmlstr += '<td><span class="wikiviewertext" projectidx=' + idx +
        '>總共' + projects[idx].wiki_pages + '篇<br>總共' + projects[idx].wiki_line +
        '行<br>總共' + projects[idx].wiki_update + '次更新</span></td>';
    /* for issue tracker */
    projectshtmlstr += '<td><span class="issuetrackerviewertext" projectidx=' +
        idx + '>總共' + projects[idx].issue_topic + '筆主題<br>總共' +
        projects[idx].issue_post + '篇文章<br>總共' + projects[idx].issue_user +
        '不同帳號參予<br>版本控制：' + projects[idx].vcs_type + '</span></td>';
    /* for vcs */
    projectshtmlstr += '<td><span class="vcsviewertext" projectidx=' + idx +
        '>總共' + projects[idx].vcs_commit + '次commit<br>總共' +
        projects[idx].vcs_line + '行<br>總共' + projects[idx].vcs_file +
        '個檔案<br>總共' + projects[idx].vcs_size + '<br>總共' +
        projects[idx].vcs_user + '不同帳號參予</span></td>';
    /* for download*/
    projectshtmlstr += '<td><span class="downloadviewertext" projectidx=' +
        idx + '>總共' + projects[idx].dl_file + '個檔案<br>總共' +
        projects[idx].dl_count + '次下載</span></td>';
    /* for star/rating */
    projectshtmlstr += '<td>';
    if (projects[idx].platform == 'googlecode') {
      projectshtmlstr += 'Starred by ' + projects[idx].star + ' users';
    } else if (projects[idx].platform == 'github') {
      projectshtmlstr += 'Star: ' + projects[idx].star + ' <br>Watched: ' +
          projects[idx].watch + '<br>Fork: ' + projects[idx].fork;
    } else if (projects[idx].platform == 'sourceforge') {
      projectshtmlstr += '5-Stars: ' + projects[idx]['5-star'] +
          '<br>4-Stars: ' + projects[idx]['4-star'] + '<br>3-Stars: ' +
          projects[idx]['3-star'] + '<br>2-Stars: ' + projects[idx]['2-star'] +
          '<br>1-Star: ' + projects[idx]['1-star'];
    } else { //openfoundry
      projectshtmlstr += 'N/A';
    }
    projectshtmlstr += '</td>';
    /* for last_update */
    projectshtmlstr += '<td>' + projects[idx].last_update + '</td>';
    /* for crawler */
    projectshtmlstr += '<td><span class="crawlertext" projectidx=' + idx +
        '>' + projects[idx].proxy_ip + '</span></td>';
    /* for status*/
    projectshtmlstr += '<td><span class="statustext" projectidx=' + idx + '>' +
        projects[idx].status + '</span></td></tr>';
  }
  $('#projecttable').append(projectshtmlstr);
  /*add listeners for hypertext in the table*/
  $('#projecttable').find('.wikiviewertext').click(function() {
    loadwikiviewer(projects[$(this).attr('projectidx')]);
  });
  $('#projecttable').find('.vcsviewertext').click(function() {
    loadvcsviewer(projects[$(this).attr('projectidx')]);
  });
  $('#projecttable').find('.issuetrackerviewertext').click(function() {
    loadissuetrackerviewer(projects[$(this).attr('projectidx')]);
  });
  $('#projecttable').find('.downloadviewertext').click(function() {
    loaddownloadviewer(projects[$(this).attr('projectidx')]);
  });
  $('#projecttable').find('.crawlertext').click(function() {
    loadcrawlerviewer(projects[$(this).attr('projectidx')]);
  });
  $('#projecttable').find('.statustext').click(function() {
    loadStatusViewer(projects[$(this).attr('projectidx')]);
  });
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
  //load select column
  loadColumnSelection('#columnselectwindow', '#projecttable');
  $('#columnselectwindow').dialog({
    title: '選擇顯示欄位',
    height: 'auto',
    width: 'auto',
    autoOpen: false,
    draggable: false,
    modal: true,
    position: {
      my: 'top',
      at: 'bottom',
      of: $('#selectcolumnbutton')
    },
    buttons: [{
      text: '全選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', true);
        hideColumns();
      }
    }, {
      text: '全不選',
      click: function() {
        $(this).find('.filtercheck').prop('checked', false);
        hideColumns();
      }
    }, {
      text: '確定',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });

});


/**
 * This function loads the column selection filter window, it does this by
 * looping through all the <th> tags of the table and getting their text value
 * for filter display and attaching a listener to hide columns whenever the
 * checkbox is checked.
 * @param {string} windowid A string containing the id of the div to render
 * the column filtering controls to .
 * @param {string} tableid The string id of the table element.
 */
function loadColumnSelection(windowid, tableid) {
  $(windowid).empty();
  $(tableid + ' th').each(function() {
    $(windowid).append('<input type="checkbox" class="filtercheck" value=' +
        ($(this).index() + 1) + ' checked>' + $(this).text() + '</input>');
  });
  $(windowid).find('.filtercheck').change(function() {
    hideColumns();
  });
}


/**
 * This function opens the column filter dialog window.
 */
function selectColumn() {
  $('#columnselectwindow').dialog('open');
}


/**
 * This function hides the columns that are not checked in the column filter
 * window. It does this by looping through each checkbox which is checked in
 * the filter window and if a column index is not in one of them, the column
 * is hidden. Else the column is shown.
 */
function hideColumns() {
  $('#columnselectwindow').find('.filtercheck').each(function() {
    var checked = $(this).prop('checked');
    var column = $(this).val();
    if (checked) $(
        '#projecttable th:nth-child(' + column +
        '),#projecttable td:nth-child(' + column + ')'
      ).show();
    else $(
        '#projecttable th:nth-child(' + column +
        '),#projecttable td:nth-child(' + column + ')'
      ).hide();
  });

}
