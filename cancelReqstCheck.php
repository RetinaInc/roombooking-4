<?php
        include("inc/login_info.inc.php");
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		forbidden();
	        exit;
	}
	session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�L���D���</title>
<link href="Level3_3.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {color: #FF0000}
-->
</style>
</head>

<body class="body2" onDragStart="return false" oncontextmenu="return false" onSelectStart="return false">
<?php
	/* POST --- >
			$_POST['usrtotal']
			$_POST['del_index#']
			$_POST['roomid'];
			$_POST['day']
			$_POST['month']
			$_POST['year']
	       < ---
	*/
	/*
	 * const data
	 */
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	$roomid=$_POST['roomid'];
	$usrtotal=$_POST['usrtotal'];

	$monthName=array("�@","�G","�T","�|","��","��","�C","�K","�E","�Q","�Q�@","�Q�G");
	$weekdayName=array("��","�@","�G","�T","�|","��","��");

	/*
	 * function declaration
	 */
	function getEmailLink($user){
		return "<a href=\"mailto:$user\">$user</a>";
	}

	$dblink=include("inc/dbcon.inc.php");

?>
<div align="center">
<?php echo "�i $room_user_id �j����Q�ǹw���R�� ";?>
</div>
<br>

<form name="cancel" method="post" action="cancelReqstDone.php">
<?php
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00','12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30','16:30 ~ 17:30',
	                '17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');


	echo "<table width=\"50%\" border=\"1\" align=\"center\">\n";
	echo "<tr>\n";
	echo "<th width=\"33%\" align=\"center\" class=\"footerHEAD\">\n";//date
	echo "���";
	echo "</th>\n";
	echo "<th width=\"33%\" align=\"center\" class=\"footer1\">\n";//time
	echo "�ɬq";
	echo "</th>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "��Q��";
	echo "</th>\n";
	echo "</tr>\n";
	
	$count_del=0;
	for($i=0;$i<$usrtotal;$i++){
		if(empty($_POST["del_index$i"]))continue;
		$rqstid=$_POST["del_index$i"];
		$query="SELECT rq.*, rm.name as roomnm from resv_rqst as rq INNER JOIN room as rm ON rq.room_id = rm.id
			WHERE rqst_id = '$rqstid';";
		$result=mysql_query($query);
		$rsdata=mysql_fetch_array($result);

		echo "<tr>\n";
		echo "<td align=\"center\" class=\"overlab\">\n";//date
		echo $rsdata['resv_date'];
		echo "</td>\n";
		echo "<td align=\"center\" class=\"overlab\">\n";//time
		echo $rownames[$rsdata['resv_row']];
		echo "</td>\n";
		echo "<td align=\"center\" class=\"overlab\">\n";//room
		echo $rsdata['roomnm'];
		echo "<input name=\"del_index$count_del\" type=\"hidden\" id=\"del_index$count_del\" value=\"$rqstid\">";
		echo "</td>\n";
		echo "</tr>\n";
		$count_del++;
	}

	if($count_del==0){
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"3\">\n";
		echo "�z�S���R���w��!";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";

	if($count_del>0){
		echo "<div align=\"center\">{{ �H�W�O�z�Y�N�R���w�����ɬq }}</div>";
		echo "<br>";
		echo "<div align=\"center\">";
		echo "<input name=\"count\" type=\"hidden\" id=\"count\" value=\"$count_del\">";
		echo "<input name=\"day\" type=\"hidden\" id=\"day\" value=\"$day\">";
		echo "<input name=\"month\" type=\"hidden\" id=\"month\" value=\"$month\">";
		echo "<input name=\"year\" type=\"hidden\" id=\"year\" value=\"$year\">";
		echo "<input name=\"roomid\" type=\"hidden\" id=\"roomid\" value=\"$roomid\">";
  		echo "<input name=\"Submit\" type=\"submit\" class=\"footer1\" id=\"Submit\" value=\"�T�w\">";
		echo "</div>";
		echo "<br>";
	}
	mysql_free_result($result);
	mysql_close($dblink);
?>

<br>
<div align="center">
<a href="javascript:history.back()">�^�e�����s���</a>
<?php
	$statuslink="https://".$_SERVER['HTTP_HOST']
	            .dirname($_SERVER['PHP_SELF'])
	            ."/week_status.php?year=$year&month=$month&day=$day&roomid=$roomid";

	echo "<a href=\"$statuslink\">�^�w���˵�����</a>";
?>
</div>

</form>

</body>
</html>
