<!DOCTYPE html>

<html>

<head>
  <title>
    <?php echo $title ?>
  </title>
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
  <link rel="stylesheet" href="<?php echo base_url();?>asset/css/Prj1.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/jquery.jqplot.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/plugins/jqplot.cursor.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/plugins/jqplot.barRenderer.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
  <script src="<?php echo base_url();?>asset/jqplot/plugins/jqplot.pointLabels.min.js"></script>
  <link rel="stylesheet" href="<?php echo base_url();?>asset/jqplot/jquery.jqplot.min.css" />
  <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script src="<?php echo base_url();?>asset/js/autocompleteprojectid.js"></script>
  <link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
</head>

<body>
  <div class='greenheader'>
    <span class='pagename'><?php echo $title ?></span>
    <span class='greetuser'>
      <b><?php echo $username ?> 歡迎您!</b>
      <button class='logoutbutton' onclick='window.location.href="<?php echo base_url()?>index.php/users/logout"'>
        登出
      </button>
    </span>
  </div>
  <nav class='adminnavbar'>
    <a href="searchtable">專案資料查詢</a> |
    <a href='import'>專案匯入</a> |
    <a href='schedule'>資料收集排程</a> |
    <a href='' onclick='return toggleSubNavBar();'>相關紀錄查詢</a> |
    <a href='setemail'>信箱設定</a>
  </nav>
  <nav class='subnavbar'>
    <a href="userLoginLog">使用者登入紀錄</a> |
    <a href="prjEditLog">專案編修紀錄</a> |
    <a href="scheduleEditLog">排程設定紀錄</a> |
    <a href="scheduleExeLog">排程執行紀錄</a> |
    <a href="queryLog">資料查詢紀錄</a> |
    <a href="deployLog">伺服器佈署紀錄</a>
  </nav>
  <script src="<?php echo base_url();?>asset/js/subnavbar.js"></script>
