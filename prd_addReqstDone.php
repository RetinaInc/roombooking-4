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
	/* 
	 * const data
	 */
	session_start();
	include("inc/lib.inc.php");
	$roomid = $_POST['roomid'];
	$room_user_id = $_SESSION['room_user_id'];

        if ( $roomid >6 && !ista($room_user_id)){
                forbidden();
                exit;
        }

	$monthName=array("¤@","¤G","¤T","¥|","¤­","¤»","¤C","¤K","¤E","¤Q","¤Q¤@","¤Q¤G");
	$weekdayName=array("¤é","¤@","¤G","¤T","¥|","¤­","¤»");
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
			'12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
			'16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');


	/*
	 * POST --> 
			ext	
			applicant
			year
			month
			day
			roomid
			roomnm
			startyy
			startmm
			startdd
			endyy
			endmm
			enddd
			weekday
			resv_row#
			supervisor
			reason
			email
			memo
	 *     <--
	 */
			$supervisor=$_POST['supervisor'];
			$reason=$_POST['reason'];
			$email=$_POST['email'];
			$memo=$_POST['memo'];
			$ext=$_POST['ext'];
			$applicant=$_POST['applicant'];
			$year=$_POST['year'];
			$month=$_POST['month'];
			$day=$_POST['day'];
			$roomnm=$_POST['roomnm'];
			$startyy=$_POST['startyy'];
			$startmm=$_POST['startmm'];
			$startdd=$_POST['startdd'];
			$endyy=$_POST['endyy'];
			$endmm=$_POST['endmm'];
			$enddd=$_POST['enddd'];
			$weekday=$_POST['weekday'];
		
	  $thedate=getdate(mktime(0,0,0,$month,$day,$year));
	  //$dateinformat=date("Y-m-d", mktime(0,0,0,$month,$day,$year));

	 /*
	  * check time
	  */
	 /*
	  * current user's reservation
	  */
	  $dblink=include("inc/dbcon.inc.php");
	  $your_table="";
	  $cflt_table="";

	  /*
           * ¶g´Á
           */
          $startdate=getdate(mktime(0,0,0,$startmm,$startdd,"20".$startyy));
          $offset = $weekday-$startdate['wday'];
          $tmpdd=$startdd+ ($offset >=0  ? $offset : 7+$offset) ;
          while(1){
                //for each week
                $tmptime=mktime(0,0,0,$startmm,$tmpdd,"20".$startyy);
                //echo $tmptime;
                //echo "<br>";
                //echo mktime(0,0,0,$endmm,$enddd,"20".$endyy);
                //echo "<br>";
                if($tmptime>mktime(0,0,0,$endmm,$enddd,"20".$endyy)){
                        break;
                }
                $tmpdate=getdate($tmptime);
	  	$dateinformat=date("Y-m-d", $tmptime);
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
		$tmpdd+=7;
	  }
	  if( $cfltcount>0 ){

?>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=big5">
		<title>µL¼ÐÃD¤å¥ó</title>
		<link href="Level3_3.css" rel="stylesheet" type="text/css">
		</head>
		<body class="body2">
		<div align="center">
		<span class="dingbatCopy">
		<?php echo "¹êÅç«Ç¹w¬ù¡@¡i".$roomnm."¡";?>
		</span>
		</div>
		<br>
		<div align="center" class="alerted">«Ü©êºp¡I±z©Ò¿ïªº®É¬q¦b±z¹w­qªº¦P®É³Q§O¤H¥ý­q¨«¤F¡I</div>
		<br>
		<div align="center">{{ ½Ä¬ð®É¬q¦Cªí }}</div>
		<table width="60%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="footer">
			<th>¤é´Á</th>
			<th>®É¬q</th>
			<th>¬ã°Q«Ç</th>
			<th>¹w¬ù¤H</th>
			<th>E-MAIL</th>
		</tr>
<?php
		echo $cflt_table;
?>
		</table> 
		<br>
		<div align="center">{{ ±z¿ï¾Üªº®É¬q¦Cªí }}</div>
		<table width="36%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="footer">
			<th>¤é´Á</th>
			<th>®É¬q</th>
			<th>¬ã°Q«Ç</th>
		</tr>
<?php
		echo $your_table;
?>
		</table> 
		<br>
		<div align="center">
		<a href="<?php echo "week_status.php?year=$year&month=$month&day=$day&roomid=$roomid" ?>">¦^ÀËµø¦Cªí¿ï¾Ü¨ä¥L®É¬q</a>
		</div>
		</body>
		</html>
<?php
		mysql_free_result($result);
		mysql_close($dblink);
	  }else{
		  //´¡¤J¸ê®Æ®w Insert this request to database
        	$startdate=getdate(mktime(0,0,0,$startmm,$startdd,"20".$startyy));
        	$offset = $weekday-$startdate['wday'];
        	$tmpdd=$startdd+ ($offset >=0  ? $offset : 7+$offset) ;
        	while(1){
                	//for each week
                	$tmptime=mktime(0,0,0,$startmm,$tmpdd,"20".$startyy);
                	if($tmptime>mktime(0,0,0,$endmm,$enddd,"20".$endyy)){
                        	break;
                	}
                	$tmpdate=getdate($tmptime);
	  		$dateinformat=date("Y-m-d", $tmptime);
			for($i=0;$i<13;$i++){
				if($resv_row[$i]!="yes")continue;
				$query="INSERT INTO resv_rqst (room_id, resv_date, resv_row, applicant, email, ext, reason, memo, 
					prof) VALUES ('$roomid','$dateinformat','$i','$room_user_id','$email','$ext','$reason',
					'$memo','$supervisor');";
				mysql_query($query) or die("database updating error!"); 
			}
			$tmpdd+=7;
		}
		mysql_free_result($result);
		mysql_close($dblink);

		header("Location: https://".$_SERVER['HTTP_HOST']
					  .dirname($_SERVER['PHP_SELF'])
					  ."/week_status.php?year=$year&month=$month&day=$day&roomid=$roomid");
	}
?>
