<?php
	/*function*/
	function getUserLink($user){
		return "<a href=\"mailto:$user\">$user</a>";
	}
        include("inc/login_info.inc.php");
	/*
	 * POST --> 
	 		reqstid
			ext	
			applicant
			year
			month
			day
			roomid
			roomnm
			supervisor
			reason
			email
	 *     <--
	 * SESSION -->
			roo_user_id
	 *     <--
	 */
	session_start();
	$reqstid = $_POST['reqstid'];
	$ext = $_POST['ext'];
	$applicant = $_POST['applicant'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$roomid = $_POST['roomid'];
	$roomn = $_POST['roomnm'];
	$supervisor = $_POST['supervisor'];
	$reason = $_POST['reason'];
	$email = $_POST['email'];
	$memo = $_POST['memo'];

	$room_user_id = $_SESSION['room_user_id'];

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
                   forbidden();
                   exit;
        }
	/* 
	 * const data
	 */
	include("inc/lib.inc.php");

        if ( $roomid >6 && !ista($room_user_id)){
                forbidden();
                exit;
        }

	$monthName=array("一","二","三","四","五","六","七","八","九","十","十一","十二");
	$weekdayName=array("日","一","二","三","四","五","六");
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
			'12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
			'16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');


	  $thedate=getdate(mktime(0,0,0,$month,$day,$year));
	  $dateinformat=date("Y-m-d", mktime(0,0,0,$month,$day,$year));

	 /*
	  * check time
	  */
	 /*
	  * current user's reservation
	  */
	  $dblink=include("inc/dbcon.inc.php");
	  $your_table="";
	  for($i=0; $i<13; $i++){
	  	$resv_row[$i]=$_POST["resv_row$i"];
		if($resv_row[$i]=="yes"){
			$your_table.="<tr align=\"center\">";
			$your_table.="<td align= \"center\" class=\"overlab\">";
			$your_table.=$dateinformat;
			$your_table.="</td>";
			$your_table.="<td align= \"center\" class=\"overlab\">";
			$your_table.=$rownames[$i];
			$your_table.="</td>";
			$your_table.="<td align= \"center\" class=\"overlab\">";
			$your_table.=$roomnm;
			$your_table.="</td>";
  			$your_table.="</tr>";
		}
	  }
	 /*
	  * overlap reservation 
	  */
	  $query="SELECT rq.*, rm.name as roomnm from resv_rqst as rq INNER JOIN room as rm ON rq.room_id = rm.id 
	  	  where resv_date = '$dateinformat' AND room_id = '$roomid' AND cancel = 'no';"; 

	  $result=mysql_query($query);
	  $cflt_table="";
	  while($rsdata=mysql_fetch_array($result)){
		  $tmprow=$rsdata['resv_row'];
		  if($resv_row[$tmprow]=="yes"){
			$cflt_table.="<tr align=\"center\">";
			$cflt_table.="<td align= \"center\" class=\"overlab\">";
			$cflt_table.=$dateinformat;
			$cflt_table.="</td>";
			$cflt_table.="<td align= \"center\" class=\"overlab\">";
			$cflt_table.=$rownames[$tmprow];
			$cflt_table.="</td>";
			$cflt_table.="<td align= \"center\" class=\"overlab\">";
			$cflt_table.=$rsdata['roomnm'];
			$cflt_table.="</td>";
			$cflt_table.="<td align= \"center\" class=\"overlab\">";
			$cflt_table.=$rsdata['applicant'];
			$cflt_table.="</td>";
			$cflt_table.="<td align= \"center\" class=\"overlab\">".getUserLink($rsdata['email'])."</td>";
  			$cflt_table.="</tr>";
		  	$cfltcount++;
		  }
	  }
	  if( $cfltcount>0 ){

?>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
		<title>無標題文件</title>
		<link href="Level3_3.css" rel="stylesheet" type="text/css">
		</head>
		<body class="body2">
		<div align="center">
		<span class="dingbatCopy">
		<?php echo "實驗室預約　【".$roomnm."】　星期".$weekdayName[$thedate['wday']]."　".$year."　年　".$month."　月　".$day."　日";?>
		</span>
		</div>
		<br>
		<div align="center" class="alerted">很抱歉！您所選的時段在您預訂的同時被別人先訂走了！</div>
		<br>
		<div align="center">{{ 衝突時段列表 }}</div>
		<table width="60%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="footer">
			<th>日期</th>
			<th>時段</th>
			<th>實驗室</th>
			<th>預約人</th>
			<th>E-MAIL</th>
		</tr>
<?php
		echo $cflt_table;
?>
		</table> 
		<br>
		<div align="center">{{ 您選擇的時段列表 }}</div>
		<table width="36%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="footer">
			<th>日期</th>
			<th>時段</th>
			<th>實驗室</th>
		</tr>
<?php
		echo $your_table;
?>
		</table> 
		<br>
		<div align="center">
		<a href="<?php echo "week_status.php?year=$year&month=$month&day=$day&roomid=$roomid" ?>">回檢視列表選擇其他時段</a>
		</div>
		</body>
		</html>
<?php
		mysql_free_result($result);
		mysql_close($dblink);
	  }else{
		  //插入資料庫 Insert this request to database
		for($i=0;$i<13;$i++){
			if($resv_row[$i]!="yes")continue;
			$query="INSERT INTO resv_rqst (room_id, resv_date, resv_row, applicant, email, ext, reason, memo, 
				prof) VALUES ('$roomid','$dateinformat','$i','$room_user_id','$email','$ext','$reason',
				'$memo','$supervisor');";
			mysql_query($query) or die("database updating error!"); 
		}
		mysql_free_result($result);
		mysql_close($dblink);

		header("Location: https://".$_SERVER['HTTP_HOST']
					  .dirname($_SERVER['PHP_SELF'])
					  ."/week_status.php?year=$year&month=$month&day=$day&roomid=$roomid");
	}
?>
