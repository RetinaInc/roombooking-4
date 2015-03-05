<?php
	/* GET --- > 
			$_GET['year']
			$_GET['month']
			$_GET['day']
			$_GET['roomid']
	       < --- 
	*/

	function getLabLink($y,$m,$d,$roomid,$linktype){
		switch($linktype){
			case 0:
				return "day_status.php?day=$d&month=$m&year=$y&roomid=$roomid";
			case 1:
				return "week_status.php?day=$d&month=$m&year=$y&roomid=$roomid";
			case 2:
				return "month_status.php?day=$d&month=$m&year=$y&roomid=$roomid";
			default:
				return "impossible.php";
		}
	}

	header("Cache-Control: no-store, no-cache, must-revalidate");

	include("Calendar.inc.php");
	include("inc/login_info.inc.php");

/*
	  if (!isset($_SERVER['PHP_AUTH_USER'])) {
		  forbidden();
		  exit;
   	  }
*/
  	session_start();
	$room_user_id=$_SESSION['room_user_id'];
	if (!isset($room_user_id)) {
		$taperm=0;
   	}else{
		$taperm=1;
	}

  	$dblink=include("inc/dbcon.inc.php");

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>無標題文件</title>
<SCRIPT Language="JavaScript">
<!--
function GO(year,month,day,roomid){ location.href = "view.php?year="+year+"&month="+month+"&day="+day+"&roomid="+roomid }
// -->
</SCRIPT>
<style type="text/css">
<!--
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}

-->
</style>
<link href="Level3_3.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	background-image: url();
}
-->
</style></head>

<!--
<body onDragStart="return false" oncontextmenu="return false" onSelectStart="return false">
-->
<body>
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">      &#8224;　Select Date 
  </td>
</tr>
</table>
<?php
  /* 
   * GET varibles
   */
   // register_global is off  20060503
  	$month=$_GET["month"];
  	$year=$_GET["year"];
  	$day=$_GET["day"];
  	$roomid=$_GET["roomid"];
  	$linktype=$_GET["linktype"]; 
  /*
   * know what day today is
   */
  $d = getdate(time());

  /*
   * assign year,month,day, and roomid value if not assigned.
   */
  if ($month == "")
  {
    $month = $d["mon"];
  }
  if ($year == "")
  {
    $year = $d["year"];
  }
  if ($day == "")
  {
    $day = $d["mday"];
  }
  if ($roomid == "")
  {
    $result = mysql_query("SELECT id FROM room where dscrp = 'oslab' LIMIT 1");
    list($roomid) = mysql_fetch_row($result);
  }
  if ($linktype =="")
  {
	  $linktype = 1;
  }

  /*
   * setup calendar
   */
  $cal = new Calendar;
  $cal->setGlobalInfo($year,$month,$day,$roomid);
  /*
   * show calendar
   */
  echo $cal->getMonthView($month, $year);
?>
<!--
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">      &#8224;　Select Meetin' Room
  </td>
</tr>
<?php
  /* 
   * list rooms with link to room-status-page
   */
/*
  $room_count=1;
  $result=mysql_query("SELECT * FROM room where dscrp IS NULL");
  while($rsarray=mysql_fetch_array($result)){
	echo "<tr>";
	$class = strcmp($rsarray['id'],$roomid)? "room$room_count" : "room".$room_count."Now" ;
	$link = "<a href=\"".getLabLink($year,$month,$day,$rsarray['id'],$linktype)."\" target=\"mainFrame\"";
	$link .= " onClick=\"GO($year,$month,$day,".$rsarray['id'].")\">".$room_count++."&nbsp;.&nbsp;&nbsp;".$rsarray['name'];
	echo "<td class=\"$class\" align=\"center\">".$link."</td>";
	echo "</tr>";
  }
*/
?>
</table>
-->
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">      &#8224;　Select PC Lab 
  </td>
</tr>
<?php
  /* 
   * list rooms with link to room-status-page
   */
  $room_count=1;
  $result=mysql_query("SELECT * FROM room where dscrp = 'oslab'");
  while($rsarray=mysql_fetch_array($result)){
	echo "<tr>";
	$class = strcmp($rsarray['id'],$roomid)? "room$room_count" : "room".$room_count."Now" ;
	$link = "<a href=\"".getLabLink($year,$month,$day,$rsarray['id'],$linktype)."\" target=\"mainFrame\"";
	$link .= " onClick=\"GO($year,$month,$day,".$rsarray['id'].")\">".$room_count++."&nbsp;.&nbsp;&nbsp;".$rsarray['name'];
	echo "<td class=\"$class\" align=\"center\">".$link."</td>";
	echo "</tr>";
  }
  mysql_free_result($result);
  mysql_close($dblink);
?>
<tr>
  <td align="center">
  [ 
  <a href="http://www.csie.nctu.edu.tw/chinese/about/building/f3.html" target="_blank">三樓平面圖</a>
  ]
  </td>
</tr>
</table>
<?php
	if($taperm==1){
?>
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">      &#8224;　TA Management
  </td>
</tr>
<tr>
  <td align="center"><a href="cancelRequest.php?<?php echo "day=$day&month=$month&year=$year&roomid=$roomid";?>" target="mainFrame"><u>Manage</u></a></td>
</tr>
<tr>
  <td align="center"><a href="logout.php" target="_parent"><u>Logout</u></a></td>
</tr>
</table>
<?php
	}else{
?>
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">      &#8224;　TA Admin
  </td>
</tr>
<tr>
  <td align="center">
<a href="https://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);?>/login.php" target="_parent">
<u>Login</u>
</a>
  </td>
</tr>
</table>
<?php
	}
?>
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="left">  &#8224;　HELP </td>
</tr>
<tr>
  <td align="center" class="body2"><a href="help.htm" target="_blank">使用說明</a></td>
</tr>
</table>
<?php
	if($taperm==1){
?>
<br>
<table width="90%"  border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
  <td align="center"><font color="darkcyan">Welcome! Dear <?php echo "<b>".$room_user_id."</b>";?> !</font></td>
</tr>
</table>
<?php
	}
?>
</body>
</html>
