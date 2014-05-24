<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
	<title>NSC</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url();?>asset/css/Prj1.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>asset/css/login.css" />
</head>
<body>  
	<center>
		<span class="bluelargetext">NSC專案執行成效資訊收集系統</span>
		<div class="loginwindow">
			<div class="floatleftcenter">
			<form action="<?php echo base_url()?>/index.php/users/login" method="post">
				<div class="floatleftregion">
					<span class="largertext">帳號：<input type="text" name="account"></input></span><br>
					<span class="largertext">密碼：<input type="password" name="password"></input></span>
				</div>
				<div class="floatleftregion">
					<input type="submit" class="loginbutton" value="登入"></input>
				</div>
			</form>
			</div>
			<div class="floatleftcenter">
			<form action="<?php echo base_url()?>/index.php/users/googlelogin" method="post">
			<span class="middletext">
              Or login with <input type="submit" class="googlelogin" value="Google"></input>
            </span>
            <br>
			</form>
			<span class="middletext">
              <span onclick="return showforgotpw();">忘記密碼</span>
              <span>  |  </span>
              <span onclick="return showregister();">線上註冊帳號</span>
            </span>
			</div>
		</div>
		<div id="registerwindow" hidden>
			<span>輸入帳號<input id="registeraccount" type="text" maxlength="30"></input></span>
			<span>輸入密碼<input id="registerpw" type="password" maxlength="64"/></span>
			<span>重新輸入密碼<input id="registerpwconfirm" type="password" maxlength="64"/></span>
			<span>輸入信箱<input id="registeremail" type="text" maxlength="30"/></span>
			<button onclick="return submitregister();">註冊</button>
		</div>
		
		<div id="forgotpwwindow" hidden>
			<span>輸入帳號<input id="forgotpwaccount" type="text"></input></span>
			<button onclick="return submitforgotpw();">將密碼寄到信箱</button>
		</div>
	</center>
	<script type="text/javascript" src="<?php echo base_url();?>asset/js/login.js"></script>
</body>
</html>
