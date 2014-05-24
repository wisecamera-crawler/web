<div class="greenwindow" class="floatinherit">
  <div class="greenwindowtitle">新增排程</div>
  <table class="fancytable">
    <tr>
      <th>周期</th>
      <th>設定</th>
      <th>目標型態</th>
      <th>目標值</th>
    </tr>
    <tr>
      <td>
        <select id="perioddl">
          <option value="weekly">每周</option>
          <option value="daily">每日</option>
          <option value="one_time">單次</option>
        </select>
      </td>
      <td id="optioncell">
        <span id="weeklyoptions" hidden>
          <select id="weekdaydl">
            <option value=1>周一</option>
            <option value=2>周二</option>
            <option value=3>周三</option>
            <option value=4>周四</option>
            <option value=5>周五</option>
            <option value=6>周六</option>
            <option value=7>周日</option>
          </select>
        </span>
        <span id="one_timeoptions" hidden>選擇日期:<input type="text" id="datepick"></input></span>
        <select id="hour"></select>點
        <select id="minute"></select>分
      </td>
      <td>
        <select id="targettype">
          <option value="yearclass">年度/類別</option>
          <option value="id">代碼</option>
        </select>
      </td>
      <td>
        <span id="yearclassform">
          選擇年度
          <select id="proj_year_dl"></select>
          選擇類別
          <select id="proj_class_dl"></select>
        </span>
        <span id="idform" hidden>
          <input id="targetids"></input>
        </span>
      </td>
    </tr>

  </table>
  <center>
    <button onclick="insertSchedule();">新增排程</button>
  </center>
</div>
<div class="greenwindow" class="floatinherit">
  <div class="greenwindowtitle">排程內容</div>
  <table class="fancytable" id="activescheduletable">
  </table>
</div>
<div class="greenwindow" class="floatinherit">
  <div class="greenwindowtitle">失效的排程內容</div>
  <table class="fancytable" id="inactivescheduletable">
  </table>
</div>
</center>

<script src="<?php echo base_url();?>asset/js/schedule.js"></script>