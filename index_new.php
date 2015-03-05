<?php
	header("Cache-Control: no-store, no-cache, must-revalidate");
/*
	  if (!isset($_SERVER['PHP_AUTH_USER'])) {
		      header("Location: https://".$_SERVER['HTTP_HOST']
		      .dirname($_SERVER['PHP_SELF'])
		      ."/"."login.php");
	  }
*/
?>
								  
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>交大資工-電腦教室預約系統</title>
</head>

<frameset rows="*" cols="230,*" framespacing="0.5" frameborder="yes" border="0.5" bordercolor="#CC0000">
  <frame src="view_new.php" name="leftFrame">
  <frame src="week_status.php" name="mainFrame">
</frameset>
<noframes><body>
</body></noframes>
</html>
