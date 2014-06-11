<div>
  <div class="floatleftquarter">
    <div class="greenwindow">
      <div class="greenwindowtitle">上傳XML檔案</div>
      <label for="file">檔案名稱：</label>
      <input type="file" name="file" id="file" />
      <br>
      <button onclick="return upload();">上傳</button>
    </div>
    <div class="greenwindow fiftypixelheight">
      <center><span>新增單筆專案：<button id="newprojbutton">新增</button></span>
      </center>
    </div>
  </div>
  <div class="floatleftthreequarter">
    <div class="greenwindow">
      <div class="greenwindowtitle">過濾選項</div>
      <table id="filteroptions" class="fancytable">
        <tr>
          <th>年度</th>
          <th>類別</th>
          <th>代碼</th>
          <th>專案</th>
          <th>平台</th>
        </tr>
        <tr>
          <td>
            <button id="filteryearbutton">過濾年度</button>
            </select>
          </td>
          <td>
            <button id="filterclassbutton">過濾類別</button>
          </td>
          <td>
            <input type="text" class="filtercell" id="idtb"></input>
          </td>
          <td>
            <input type="text" class="filtercell" id="projectnametb"></input>
          </td>
          <td>
            <button id="filterplatformbutton">過濾平台</button>
          </td>
        </tr>

      </table>
    </div>
    <div class="greenwindow" id="deleteprojectwindow">
      <div class="greenwindowtitle">管理專案</div>

    </div>

    <div id="newprojectwindow" hidden>
      <center>
        <span>
          年度:<input type = "text" id="newprojyear" maxlength="11"></input>
          類別:<input type = "text" id="newprojclass" maxlength="16"></input>
          代碼:<input type = "text" id="newprojid" maxlength="32"></input>
          專案名稱:<input type = "text" id="newprojname" maxlength="64"></input>
          主持人:<input type = "text" id="newprojleader" maxlength="32"></input>
          平台/網址:<input type = "text" id="newprojplatform" class="twohundredpixelwidth" maxlength="128"></input>
        </span>
      </center>
      <center>
        <button id="newprojok">確認</button>
        <button id="newprojcancel">取消</button>
      </center>
    </div>

    <div id="modprojectwindow" hidden>
      <center>
        <span>
          年度:<input type = "text" id="modprojyear"  maxlength="11"></input>
          類別:<input type = "text" id="modprojclass"  maxlength="16"></input>
          代碼:<input type = "text" id="modprojid" readonly></input>
          專案名稱:<input type = "text" id="modprojname"  maxlength="64"></input>
          主持人:<input type = "text" id="modprojleader"  maxlength="32"></input>
          平台/網址:<input type = "text" id="modprojplatform" class="twohundredpixelwidth"  maxlength="128"></input>
        </span>
      </center>
      <center>
        <button id="modsubmit">確認</button>
        <button id="modcancel">取消</button>
      </center>
    </div>
  </div>
</div>
</div>
<div id="filteryearwindow" hidden>
</div>
<div id="filterplatformwindow" hidden>
</div>
<div id="filterclasswindow" hidden>
</div>
<div id="confirmwindow" hidden>
</div>
<div id="querywindow" hidden>
</div>
<script src="<?php echo base_url();?>asset/js/import.js"></script>
