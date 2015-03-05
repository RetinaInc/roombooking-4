<?php
	include("inc/login_info.inc.php");
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
	         forbidden();
	         exit;
	}
	session_start();
	/*
	 * POST --> 
	 		reqst_id
			year
			month
			day
			labid
			reason
			reasontext

			count
			del_index#
	 *     <--
	 */
	  $year=$_POST['year'];
	  $month=$_POST['month'];
	  $day=$_POST['day'];
	  $roomid=$_POST['roomid'];
	$count=$_POST['count'];
	  
	  $thedate=getdate(mktime(0,0,0,$month,$day,$year));
	  if(empty($year)){
		  $year=$thedate['year'];
	  }
	  if(empty($month)){
		  $month=$thedate['mon'];
	  }
	  if(empty($day)){
		  $day=$thedate['mday'];
	  }

	  $dblink=include("inc/dbcon.inc.php");
	  
	  for($i=0;$i<$count;$i++){
		$rqstid=$_POST["del_index$i"];
		$updatequery="UPDATE resv_rqst SET cancel = 'yes' WHERE rqst_id ='$rqstid'";
		mysql_query($updatequery);
	  }
			
	  /*
	   * redirection
	   */
	  mysql_close($dblink);
	  header("Location: https://".$_SERVER['HTTP_HOST']
					  .dirname($_SERVER['PHP_SELF'])
					  ."/week_status.php?year=$year&month=$month&day=$day&roomid=$roomid");
?>
