<?php
        include("inc/login_info.inc.php");
	include("inc/lib.inc.php");
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
                forbidden();
                exit;
        }
	session_start();
	$room_user_id = $_SESSION['room_user_id'];
	$roomid = $_GET['roomid'];

	if ( $roomid >6 && !ista($room_user_id)){
		forbidden();
		exit;
	}
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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a ext-phone number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a ext-phone number '+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body class="body2" onDragStart="return false" oncontextmenu="return false" onSelectStart="return false">
<?php
	/* GET --- >
			$_GET['year']
			$_GET['month']
			$_GET['day']
			$_GET['roomid']
			$_GET['roomnm']
			$_GET['resv_row#']
	       < ---
	*/
			$year=$_GET['year'];
			$month=$_GET['month'];
			$day=$_GET['day'];
			$roomnm=$_GET['roomnm'];
			
	/*
	 * const data
	 */
	$monthName=array("一","二","三","四","五","六","七","八","九","十","十一","十二");
	$weekdayName=array("日","一","二","三","四","五","六");

	/*
	 * function declaration
	 */
	function getUserLink($user){
		return "<a href=\"mailto:$user\">$user</a>";
	}

	/*
	 * setup GET variables
	 */

	/*
	 * 	dateinformat : date in correct format
	 */
	$thedate=getdate(mktime(0,0,0,$month,$day,$year));
	$dateinformat=date("Ymd", mktime(0,0,0,$month,$day,$year));

	for($i=0;$i<13;$i++){
		$resv_row[$i]=$_GET["resv_row$i"];
	}
?>
<div align="center">
<span>
<?php 
	if($roomid<7){
	echo "研討室預約　【".$roomnm."】　星期".$weekdayName[$thedate['wday']]."　".$year."　年　".$month."　月　".$day."　日";
}else{
	echo "實驗室預約　【".$roomnm."】　星期".$weekdayName[$thedate['wday']]."　".$year."　年　".$month."　月　".$day."　日";
}
?>
</span>
</div>
<br>
<br>

<div align="center">{{ 您即將預約的時段如下 }}</div>
<?php
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
			'12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
			'16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');


	echo "<table width=\"50%\" border=\"1\" align=\"center\">\n";
	echo "<tr>\n";
	echo "<th width=\"33%\" align=\"center\" class=\"footerHEAD\">\n";//date
	echo "日期";
	echo "</th>\n";
	echo "<th width=\"33%\" align=\"center\" class=\"footer1\">\n";//time
	echo "時段";
	echo "</th>\n";
	echo "<th width=\"34%\" align=\"center\" class=\"footerHEAD\">\n";//room
	if($roomid<7){
		echo "研討室";
	}else{
		echo "實驗室";
	}
	echo "</th>\n";
	echo "</tr>\n";
	for($i=0;$i<13;$i++){
		if(strlen($resv_row[$i])==0)continue;
		echo "<tr>\n";
		echo "<td align=\"center\" class=\"footerHEAD\">\n";//date
		echo $year."-".$month."-".$day;
		echo "</td>\n";
		echo "<td align=\"center\" class=\"footer1\">\n";//time
		echo $rownames[$i];
		echo "</td>\n";
		echo "<td align=\"center\" class=\"footerHEAD\">\n";//room
		echo $roomnm;
		echo "</td>\n";
		echo "</tr>\n";
		$count_row++;
	}
	if($count_row==0){
		echo "<tr>\n";
		echo "<td align=\"center\" colspan=\"3\">\n";
		echo "您沒有預約!";
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
?>
<br>
<br>
<form name="form1" method="post" action="addReqstDone.php">
<div align="center">{{ 請在此填寫預約人資訊 }}</div>
  <table border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td align="center" class="footerHEAD">Applicant</td>
  <td class="footer1"><input name="supervisor" type="text" id="supervisor"></td>
</tr>
<tr>
  <td align="center" class="footerHEAD">Reason:</td>
  <td class="footer1"><p>
    <label>
    <input type="radio" name="reason" value="course" checked>
  Course</label>
    <br>
    <label>
    <input type="radio" name="reason" value="lab">
  Lab</label>
    <br>
    <label>    </label><label>
  <input type="radio" name="reason" value="other">
  Other</label> 
    ( -&gt; memo)</p></td>
</tr>
<tr>
  <td align="center" width="50%" class="footerHEAD">E-mail:</span></td>
  <td width="50%" class="footer1">
      <div align="left">
        <input name="email" type="text" id="email" maxlength="50">
    </div></td>
</tr>
<tr>
  <td align="center" class="footerHEAD">EXT:</td>
  <td class="footer1">
      <div align="left">
        <input name="ext" type="text" id="ext" maxlength="10">
    </div></td></tr>
<tr>
  <td align="center" class="footerHEAD">Memo:</td>
  <td class="footer1"><input name="memo" type="text" id="memo" maxlength="30"></td>
</tr>
</table>
<div align="center">(若為其他預約理由，請於 Memo 欄位註明)</div>
<br><br>
<?php
	for($i=0;$i<13;$i++){
		if(strlen($resv_row[$i])>0){
			echo "<input type=\"hidden\" name=\"resv_row$i\" value=\"yes\">";
		}
	}
?>
<input type="hidden" name="year" value="<?php echo $year;?>">
<input type="hidden" name="day" value="<?php echo $day;?>">
<input type="hidden" name="month" value="<?php echo $month;?>">
<input type="hidden" name="roomid" value="<?php echo $roomid;?>">
<input type="hidden" name="roomnm" value="<?php echo $roomnm;?>">
<input type="hidden" name="applicant" value="<?php echo $room_user_id;?>">

<div align="center">
<input name="Submit" type="submit" class="footer1" id="Submit" onClick="MM_validateForm('supervisor','','R','email','','RisEmail','ext','','RinRange10000:99999');return document.MM_returnValue" value="確定">
</div>

<br>
<div align="center">
<a href="javascript:history.back()">取消申請回前頁</a>
</div>

</form>

</body>
</html>
