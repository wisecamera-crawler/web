<center>
  <div class="greenwindow">
    <div class="greenwindowtitle">過濾選項</div>
    <table id="filteroptions" class="fancytable">
      <tr>
        <th>年度</th>
        <th>類別</th>
        <th>代碼</th>
        <th>專案</th>
        <th>平台</th>
        <th>顯示欄位</th>
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
        <td>
          <button id="selectcolumnbutton" onclick="selectColumn();">選擇欄位</button>
        </td>
      </tr>

    </table>
  </div>
</center>
<hr>
<center>
  <div class="greenwindow">
    <div class="greenwindowtitle">符合條件之專案</div>
    <table id="projecttable" class="fancytable">
      <tr>
        <th class="sortcontrol" value="year"><span>年度<div class="sorticon"></div></span>
        </th>
        <th class="sortcontrol" value="class">類別
          <div class="sorticon"></div>
        </th>
        <th class="sortcontrol" value="id">代碼
          <div class="arrow-down"></div>
        </th>
        <th class="sortcontrol" value="projectname">專案
          <div class="sorticon"></div>
        </th>
        <th class="sortcontrol" value="projecthost">主持人
          <div class="sorticon"></div>
        </th>
        <th class="sortcontrol" value="platform">平台
          <div class="sorticon"></div>
        </th>
        <th>Wiki</th>
        <th>Issue Tracker</th>
        <th>VCS</th>
        <th>Downloads</th>
        <th>Star
          <br>Rating</th>
        <th>日期
          <br>時間</th>
        <th>Proxy</th>
        <th>狀態</th>
      </tr>
    </table>
  </div>
</center>
<div id="filteryearwindow" hidden>
</div>
<div id="filterplatformwindow" hidden>
</div>
<div id="filterclasswindow" hidden>
</div>
<div id="wikiviewer" hidden>
  <div id="chartcontainer">
    <span>顯示前
		<select class="numdatas">
		  <option value="100">100</option>
		  <option value="500">500</option>
		  <option value="1000">1000</option>
		  <option value="5000">5000</option>
		  <option value="all">全部</option>
		</select>
		筆資料
		</span>
    <span>顯示：
		<select class="displaytype">
		  <option value="totalthreads">總篇數</option>
		  <option value="totallines">總行數</option>
		  <option value="totalupdates">總更新次數</option>
		  <option value="singlethreadupdates">單一篇更新次數</option>
		  <option value="singlethreadlines">單一篇行數</option>
		</select>
		<span class = "singlethreadpanel" hidden>
		選擇Wiki文章:
		<select class="threadnamelist">
		</select>
		</span>
    </span>

    <hr>
    <center>

      <div id="wikigraph" class="graphdiv"></div>
    </center>
  </div>
</div>
<div id="issuetrackerviewer" hidden>
  <span>顯示前
		<select class="numdatas">
		  <option value="100">100</option>
		  <option value="500">500</option>
		  <option value="1000">1000</option>
		  <option value="5000">5000</option>
		  <option value="all">全部</option>
		</select>
		筆資料
		</span>
  <span>顯示：
		<select class="displaytype">
		  <option value="totalthreads">主題總數</option>
		  <option value="totalreplies">回應總數</option>
		  <option value="totalaccounts">不同帳號總數</option>
		</select>
		</span>
  </span>

  <hr>
  <center>

    <div id="issuetrackergraph" class="graphdiv"></div>
  </center>
</div>
<div id="vcsviewer" hidden>
  <div>
    <span>顯示前
		<select class="numdatas">
		  <option value="100">100</option>
		  <option value="500">500</option>
		  <option value="1000">1000</option>
		  <option value="5000">5000</option>
		  <option value="all">全部</option>
		</select>
		筆資料
		</span>
    <span>顯示：
		<select class="displaytype">
		  <option value="totalcommits">總commit數</option>
		  <option value="totallines">總行數</option>
		  <option value="totalfiles">總檔案數</option>
		  <option value="totalfilesize">總檔案大小</option>
		  <option value="totalusers">總貢獻者數</option>
		  <option value="usercontribution">使用者貢獻</option>
		</select>
		<span class = "contributionpanel" hidden>
		選擇查詢日期:
		<select class="datelist">
		</select>
		</span>
    </span>

    <hr>
    <center>

      <div id="vcsgraph" class="graphdiv"></div>
  </div>
  <div id="downloadviewer" hidden>
    <span>顯示前
		<select class="numdatas">
		  <option value="100">100</option>
		  <option value="500">500</option>
		  <option value="1000">1000</option>
		  <option value="5000">5000</option>
		  <option value="all">全部</option>
		</select>
		筆資料
		</span>
    <span>顯示：
		<select class="displaytype">
		  <option value="totalfiles">總檔案數</option>
		  <option value="totaldownloads">總下載次數</option>
		  <option value="singledownloads">單一檔案下載次數</option>
		</select>
		<span class = "singlethreadpanel" hidden>
		選擇單一檔案:
		<select class="threadnamelist">
		</select>
		</span>
    </span>

    <hr>
    <center>

      <div id="downloadgraph" class="graphdiv"></div>
    </center>
  </div>
  <div id="statusviewer" hidden>
    選擇類型:
    <select class="statusviewtype">
      <option value="wiki" selected>Wiki</option>
      <option value="vcs">VCS</option>
      <option value="issue">Issue Tracker</option>
      <option value="download">Download</option>
    </select>
    <br>選擇要觀看的次數:
    <br>
    <input type="checkbox" class="showplot" value="all_success" checked>成功</input>
    <input type="checkbox" class="showplot" value="success_update" checked>資料改變且匯入成功</input>
    <input type="checkbox" class="showplot" value="no_change" checked>資料無改變</input>
    <input type="checkbox" class="showplot" value="fail" checked>失敗</input>
    <input type="checkbox" class="showplot" value="cannot_get_data" checked>無法取得資料頁面</input>
    <input type="checkbox" class="showplot" value="can_not_resolve" checked>解析失敗</input>
    <div id="statusgraph" class="graphdiv"></div>

  </div>
  <div id="crawlerviewer" hidden>
    選擇爬蟲:
    <select class="crawlerselect"></select>
    <br>選擇要觀看的資訊:
    <br>
    <input type="checkbox" class="showplot" value="all_success" checked>成功次數</input>
    <input type="checkbox" class="showplot" value="success_update" checked>資料改變且匯入成功次數</input>
    <input type="checkbox" class="showplot" value="no_change" checked>資料無改變次數</input>
    <input type="checkbox" class="showplot" value="fail" checked>失敗次數</input>
    <div id="crawlergraph" class="graphdiv"></div>
  </div>
  <div id="columnselectwindow">

  </div>
  <script type="text/javascript" src="<?php echo base_url();?>asset/js/searchtable.js"></script>
