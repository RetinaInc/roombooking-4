<?php
	/*function*/
	function getUserLink($user){
		return "<a href=\"mailto:$user\">$user</a>";
	}
	include("inc/login_info.inc.php");
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		         forbidden();
		         exit;
	}
	$dblink=include("inc/dbcon.inc.php");
	/* 
	 * const data
	 */
	session_start();

	$year=$_GET['year'];
	$month=$_GET['month'];
	$day=$_GET['day'];
	$roomid=$_GET['roomid'];

 	$dateinformat = date("Y-m-d",mktime(0,0,0,$month,$day,$year));


	$monthName=array("�@","�G","�T","�|","��","��","�C","�K","�E","�Q","�Q�@","�Q�G");
	$weekdayName=array("��","�@","�G","�T","�|","��","��");
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00','12:00 ~ 13:30','13:30 ~ 14:30',
			'14:30 ~ 15:30','15:30 ~ 16:30','16:30 ~ 17:30',
	                '17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');

	/*
	 * roomnm
	 */
	 $query="SELECT name from room where id = '$roomid'";
	 $result=mysql_query($query);
	 list($roomnm)=mysql_fetch_row($result);
	/*
	 * user's reservation 
	 */
	 $query="SELECT rq.*, rm.name as roomnm from resv_rqst as rq INNER JOIN room as rm ON rq.room_id = rm.id 
	 	 where resv_date = '$dateinformat' AND room_id = '$roomid' AND cancel = 'no'
		 ORDER BY resv_date, resv_row;"; 

	  $result=mysql_query($query);
	  $cfltcount=0;
	  $cflt_table="";
	  while($rsdata=mysql_fetch_array($result)){
		$id=$rsdata['rqst_id'];
		$cflt_table.="<tr align=\"center\">";
		$cflt_table.="<td align= \"center\" class=\"overlab\">";
	     $cflt_table.="<input name=\"del_index$cfltcount\" type=\"checkbox\" id=\"del_index$cfltcount\" value=\"$id\">";
		$cflt_table.="</td>";
		$cflt_table.="<td align= \"center\" class=\"overlab\">";
		$cflt_table.=$rsdata['resv_date'];
		$cflt_table.="</td>";
		$cflt_table.="<td align= \"center\" class=\"overlab\">";
		$cflt_table.=$rownames[$rsdata['resv_row']];
		$cflt_table.="</td>";
		$cflt_table.="<td align= \"center\" class=\"overlab\">";
		$cflt_table.=$rsdata['roomnm'];
		$cflt_table.="</td>";
		$cflt_table.="<td align= \"center\" class=\"overlab\">";
		$cflt_table.=$rsdata['applicant'];
		$cflt_table.="</td>";
		$cfltcount++;
	  }
	  mysql_free_result($result);
	  mysql_close($dblink);
?>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
		<title>�L���D���</title>
		<link href="Level3_3.css" rel="stylesheet" type="text/css">
		</head>
		<body class="body2" onDragStart="return false" oncontextmenu="return false" onSelectStart="return false">
		<div align="center" class="room">
		<?php echo "$year �~ $month �� $day �� $roomnm ���w�����p�p�U";?>
		</div>
		<br>
		<form action="cancelReqstCheck.php" method="post" name="cancel" id="cancel">
		<table width="77%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="f<?php echo $roomid%6;?>ooter">
			<th>�R��</th>
			<th>���</th>
			<th>�ɬq</th>
			<th>��Q��</th>
			<th>�w���H</th>
		</tr>
<?php
		if($cfltcount>0){
			echo $cflt_table;
		}else{
			echo "<tr>";
			echo "<td colspan=\"5\" class=\"overlab\" align=\"center\">";
			echo "�o�ѳo�ӱЫǨS���w�� !";
			echo "</td>";
		        echo "</tr>";
		}
?>
		</table> 
<?php
		if($cfltcount>0){
?>
		<br>
		<div align="center">
		<input type="hidden" name="usrtotal" value="<?php echo $cfltcount;?>">
		<input type="hidden" name="day" value="<?php echo $day;?>">
		<input type="hidden" name="month" value="<?php echo $month;?>">
		<input type="hidden" name="year" value="<?php echo $year;?>">
		<input type="hidden" name="roomid" value="<?php echo $roomid;?>">
		<input type="submit" name="submit" value="�R���ɬq">
		<input type="reset" name="reset" value="�����Ŀ�">
		</div>
<?php
		}else{
?>
		<br>
		<div align="center">
		<a href="javascript:history.back()">�^�e��</a>
<?php
		echo "<a href=\"https://".$_SERVER['HTTP_HOST']
                              .dirname($_SERVER['PHP_SELF'])
			      ."/week_status.php?year=$year&month=$month&day=$day&roomid=$roomid\">";
		echo "�^�˵��C��</a>";

		}
?>
		</div>
		</form>
</body>
</html>
