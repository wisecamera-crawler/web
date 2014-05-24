<center>
  <div class="greenwindow halfwidth">
    <table class="fancytable">
      <tr>
        <td>設定E-mail</td>
        <td>
          <input type="text" id="emailinput"></input>
        </td>
        <td>
          <button onclick="submitEmail();">確認</button>
        </td>
      </tr>
    </table>
  </div>

  <div class="greenwindow halfwidth">
    <table class="fancytable" id="emailtable">
      <tr>
        <th>已設定E-mail</th>
        <th>刪除</th>
      </tr>
      <?php
      foreach ($emails as $mail) {
          echo '<tr><td>'.$mail.'</td><td><button onclick="deleteEmail(this);">X</button></td></tr>';
      }
      ?>
    </table>
  </div>
</center>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/email.js"></script>
