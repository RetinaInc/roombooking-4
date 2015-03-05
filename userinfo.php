<?php
        include("inc/login_info.inc.php");
/*
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
		forbidden();
		exit;
	}
*/
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
		$taperm=0;
	}else{
		$taperm=1;
	}
	session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>無標題文件</title>
<link href="Level3_3.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {color: #FF0000}
-->
</style>
</head>

<body class="body2">
<?php
	/* GET --- >
			$_GET['roomnm']
			$_GET['rqstid']
			$_GET['roomid']
	       < ---
	*/
		$roomnm=$_GET['roomnm'];
		$rqstid=$_GET['rqstid'];
		$roomid=$_GET['roomid'];
	/*
	 * const data
	 */
	$monthName=array("一","二","三","四","五","六","七","八","九","十","十一","十二");
	$weekdayName=array("日","一","二","三","四","五","六");

	$dblink=include("inc/dbcon.inc.php");

?>
<div align="center" class="room<?php echo $roomid?>">
<span>
<?php echo "研討室【".$roomnm."】　預約者資料 ";?>
</span>
</div>
<br>
<br>

<div align="center">{{ 預約者聯絡資訊 }}</div>
<br>
<?php
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
			'12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
			'16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');

	$result=mysql_query("SELECT * from resv_rqst where rqst_id = '$rqstid'");
	$rsdata=mysql_fetch_array($result);

	echo "<table width=\"50%\" border=\"1\" align=\"center\">\n";
	echo "<tr>\n";
	echo "<th align=\"center\" class=\"footerHEAD\">\n";//date
	echo "日期";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $rsdata['resv_date'];
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"33%\" align=\"center\" class=\"footerHEAD\">\n";//time
	echo "時段";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $rownames[$rsdata['resv_row']];
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "研討室";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $roomnm;
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "預約人";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $rsdata['prof'];
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "承辦助教";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $rsdata['applicant'];
	echo "</td>\n";
	echo "</tr>\n";
	if( strlen($rsdata['prof']) >0 ){
		echo "<tr>\n";
		echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
		echo "預約人";
		echo "</th>\n";
		echo "<td align=\"center\" class=\"footer1\">\n";
		echo $rsdata['prof'];
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "E-mail";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo ($rsdata['email']?"<a href=\"mailto:$rsdata[email]\">$rsdata[email]</a>":"-");
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "分機";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo ($rsdata['ext']?$rsdata['ext']:"-");
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "借用事由";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo $rsdata['reason'];
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	echo "備註";
	echo "</th>\n";
	echo "<td align=\"center\" class=\"footer1\">\n";
	echo ($rsdata['memo']?$rsdata['memo']:"-");
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	mysql_free_result($result);
	mysql_close($dblink);
?>
<br>
<div align="center">
<a href="javascript:history.back()">回前頁</a>
</div>

</body>
</html>
