<?php
	        include("inc/login_info.inc.php");
/*
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		            forbidden();
		            exit;
		}
*/
		session_start();

		$room_user_id = $_SESSION['room_user_id'];
		$room_user_group = $_SESSION['room_user_group'];

		if (!isset($room_user_id)) {
			$taperm=0;
		}else{
			$taperm=1;
		}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�L���D���</title>
<link href="Level3_3.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-size: 14px}
-->
</style>
</head>

<body class="body2" onDragStart="return false" oncontextmenu="return false" onSelectStart="return false">
<?php
	/* GET --- >
			$_GET['year']
			$_GET['month']
			$_GET['day']
			$_GET['roomid']
	       < ---

	   SESSION >
	   		$_SESSION['room_user_id']
			$_SESSION['room_user_group']
	*/
	function getUserLink($user,$tg_rqst_id,$rnm,$rid){
		return "<a href=\"userinfo.php?rqstid=$tg_rqst_id&roomnm=$rnm&roomid=$rid\">$user</a>";
	}

	include("inc/lib.inc.php");
	$dblink=include("inc/dbcon.inc.php");


	$monthName=array("�@","�G","�T","�|","��","��","�C","�K","�E","�Q","�Q�@","�Q�G");
	$weekdayName=array("��","�@","�G","�T","�|","��","��");

	$day = $_GET['day'];
	$month = $_GET['month'];
	$year = $_GET['year'];
	$roomid = $_GET['roomid'];
	
        if($day==""){
  	  $today = getdate(time());
	  $day = $today['mday'];
	  $month = $today['mon'];
	  $year = $today['year'];
	}  
	if($roomid==""){
		$result=mysql_query("SELECT id FROM room where dscrp = 'defence' LIMIT 1");
		list($roomid)=mysql_fetch_row($result);
	}
	list($roomnm)=mysql_fetch_row(mysql_query("SELECT name FROM room where id = '$roomid'"));
	$thedate=getdate(mktime(0,0,0,$month,$day,$year));
	$wday=$thedate['wday'];
?>
<div align="center">
<span >
<div class="room<?php echo $roomid%6;?>">
<?php 
if ($roomid <7 ){
	echo "�i".$roomnm."�j�@��Q�Ǫ��p�@�P��".$weekdayName[$wday]."�@".$year."�@�~�@".($month)."�@��@".$day."�@��";
}else{
	echo "�i".$roomnm."�j�@����Ǫ��p�@�P��".$weekdayName[$wday]."�@".$year."�@�~�@".($month)."�@��@".$day."�@��";
}
?>
</div>
</span>
</div>
<br>
<?php
	/*
	 * �N�w�Q�w�������Ū�X��
	 */
	for($i=0;$i<7;$i++){//i: week day
		$offset=$i-$wday;
		$query="SELECT rqst_id, resv_row, applicant, prof from resv_rqst WHERE room_id = '$roomid' and 
			resv_date = ADDDATE('$year-$month-$day', INTERVAL $offset DAY)
			and cancel = 'no';"; 


		$result=mysql_query($query);
		while($rqstdata=mysql_fetch_array($result)){
			$resv_row=$rqstdata['resv_row'];
			$applicant=$rqstdata['prof'];
			$tmprqst_id=$rqstdata['rqst_id'];
			$weekstatus[$i][$resv_row]=$applicant;
			$rqstid[$i][$resv_row]=$tmprqst_id;
		}
	}
	mysql_free_result($result);
	mysql_close($dblink);

  	$today = getdate(time());
	$tday = $today['mday'];
	$tmonth = $today['mon'];
	$tyear = $today['year'];
/*
	if($roomid<7){
		if ($roomid==2){
			if( getgroup($room_user_id)=="faculty"){
				if(mktime(0,0,0,$tmonth,$tday+7,$tyear)>=mktime(0,0,0,$month,$day,$year)){
					$selectable="yes";
				}
			}
		}else{
			if(mktime(0,0,0,$tmonth,$tday+14,$tyear)>=mktime(0,0,0,$month,$day,$year)){
				$selectable="yes";
			}
		}
	}
	if($roomid>6 && ista($room_user_id)){
		$selectable="yes";
	}
*/
	if($taperm==1 && ista($room_user_id)){
		$selectable="yes";
	}

	if ($selectable=="yes" ){
		echo "<form action=\"addReqst.php\" method=\"get\" name=\"resv\" id=\"resv\">";
	}
?>
<table width="85%" border="1" align="center" >
  <tr class="f<?php echo $roomid%6;?>ooter">
    <th width="16%"><div align="center"><?php echo $year;?></div></th>
<?php
   for($cwd=0;$cwd<7;$cwd++){
	$offset=$cwd-$wday;
	$thisdate=getdate(mktime(0,0,0,$month,$day+$offset,$year));
	$thismon=$thisdate['mon'];
	$thismday=$thisdate['mday'];
   	echo "<th width=\"12%\"><div align=\"center\">$thismon/$thismday</div></th>";
   }
?>
  </tr>
  <tr class="f<?php echo $roomid%6;?>ooter1">
    <th width="16%"><div align="center">Time</div></th>
    <th width="12%"><div align="center">��</div></th>
    <th width="12%"><div align="center">�@</div></th>
    <th width="12%"><div align="center">�G</div></th>
    <th width="12%"><div align="center">�T</div></th>
    <th width="12%"><div align="center">�| </div></th>
    <th width="12%"><div align="center">��</div></th>
    <th width="12%"><div align="center">��</div></th>
  </tr>
<?php
	$rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
	                '12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
			'16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');

	for($i=0 ; $i< count($rownames) ; $i++){//i:row, j: week day 
		if($i==4 || $i==9){
			echo '<tr class="footer2">';
		}else{
			echo '<tr>';
		}
		echo "\n";
		echo '<td><div align="center">'.$rownames[$i].'</div></td>';
		echo "\n";
		for($j=0;$j<7;$j++){
			echo '<td align="center">';
			echo "\n";
			if(strlen($weekstatus[$j][$i])){
				echo getUserLink($weekstatus[$j][$i],$rqstid[$j][$i],$roomnm,$roomid);
			}else{
				if($j==$wday && $selectable=="yes"){
					echo "<input name=\"resv_row$i\" type=\"checkbox\" id=\"resv_row$i\" value=\"yes\">";
				}else{
					echo '&nbsp;';
				}
			}
			echo "\n";
			echo '</td>';
			echo "\n";
		}
		echo '</tr>';
		echo "\n";
	}
?>
</table>
<?php
	if ($selectable=="yes" ){
?>
<div align="center">

  <input type="hidden" name="year" value="<?php echo $year;?>">
  <input type="hidden" name="day" value="<?php echo $day;?>">
  <input type="hidden" name="month" value="<?php echo $month;?>">
  <input type="hidden" name="roomid" value="<?php echo $roomid;?>">
  <input type="hidden" name="roomnm" value="<?php echo $roomnm;?>">

  <input type="submit" name="submit" class="f<?php echo $roomid%6;?>ooter"  value="�w�q�ɬq">
  <input type="reset" name="reset" class="f<?php echo $roomid%6;?>ooter" value="�M������">
</div>
</form>
<?php
		if ( false ){//is pclab
?>
<form action="prd_addReqst.php" method="get" name="period" id="period">
  <table width="85%" border="1" align="center" bordercolor="#000000">
    <tr>
      <td colspan="5"><div align="center" class="f<?php echo $roomid%6?>ooter">�g���ʹw��</div></td>
    </tr>
    <tr>
      <td colspan="5" class="f<?php echo $roomid%6?>ooter1">1. �w�q�g�� </td>
    </tr>
    <tr class="f<?php echo $roomid%6;?>ooter">
      <td colspan="2"><strong>�_�l���: </strong> <select name="startyy" class="promo" id="startyy">
<option value="10">10</option>
              <option value="09">09</option>
              <option value="08">08</option>
              <option value="07">07</option>
              <option value="06">06</option>
              <option value="05" selected>05</option>
              <option value="04">04</option>
              <option value="03">03</option>
              <option value="02">02</option>
              <option value="01">01</option>
            </select>
          �~            
          <select name="startmm" class="promo" id="startmm">
            <option value="1" selected>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select> 
�� 
<select name="startdd" class="promo" id="startdd">
      <option value=1 selected>1</option>
      <option value=2>2</option>
      <option value=3>3</option>
      <option value=4>4</option>
      <option value=5>5</option>
      <option value=6>6</option>
      <option value=7>7</option>
      <option value=8>8</option>
      <option value=9>9</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
</select>
 ��</td>
      <td colspan="2"><strong>�������: </strong> <select name="endyy" class="promo" id="endyy">
      <option value="10">10</option>
            <option value="09">09</option>
            <option value="08">08</option>
            <option value="07">07</option>
            <option value="06">06</option>
            <option value="05" selected>05</option>
            <option value="04">04</option>
            <option value="03">03</option>
            <option value="02">02</option>
            <option value="01">01</option>
          </select>
�~
<select name="endmm" class="promo" id="endmm">
      <option value="1" selected>1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
</select>
��
<select name="enddd" class="promo" id="enddd">
      <option value=1 selected>1</option>
      <option value=2>2</option>
      <option value=3>3</option>
      <option value=4>4</option>
      <option value=5>5</option>
      <option value=6>6</option>
      <option value=7>7</option>
      <option value=8>8</option>
      <option value=9>9</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
</select>
��</td>
      <td><strong>�C�g: </strong>
        <select name="weekday" class="promo" id="weekday">
          <option value="1" selected>�@</option>
          <option value="2">�G</option>
          <option value="3">�T</option>
          <option value="4">�|</option>
          <option value="5">��</option>
          <option value="6">��</option>
          <option value="0">��</option>
            </select>      </td>
    </tr>
    <tr>
      <td colspan="5" class="f<?php echo $roomid%6;?>ooter1">2. ��ܮɬq </td>
    </tr>
    <tr class="room<?php echo $roomid%6;?>">
      <td><DIV align=center>08:00 ~ 09:00</DIV></td>
      <td><DIV align=center>09:00 ~ 10:00</DIV></td>
      <td><DIV align=center>10:00 ~ 11:00</DIV></td>
      <td><DIV align=center>11:00 ~ 12:00</DIV></td>
      <td><DIV align=center>12:00 ~ 13:30</DIV></td>
    </tr>
    <tr class="room<?php echo $roomid%6;?>">
      <td><div align="center">
        <input name="resv_row0" type="checkbox" id="resv_row0" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row1" type="checkbox" id="resv_row1" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row2" type="checkbox" id="resv_row2" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row3" type="checkbox" id="resv_row3" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row4" type="checkbox" id="resv_row4" value="yes">
      </div></td>
    </tr>
    <tr class="footer2">
      <td><div align="center">
        <DIV align=center>13:30 ~ 14:30</DIV>
      </div></td>
      <td><div align="center">
        <DIV align=center>14:30 ~ 15:30</DIV>
      </div></td>
      <td><div align="center">
        <DIV align=center>15:30 ~ 16:30</DIV>
      </div></td>
      <td><div align="center">
        <DIV align=center>16:30 ~ 17:30</DIV>
      </div></td>
      <td>
      <div align="center">
        <DIV align=center>17:30 ~ 18:30</DIV>
      </div></td>
    </tr>
    <tr class="footer2">
      <td><div align="center">
        <input name="resv_row5" type="checkbox" id="resv_row5" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row6" type="checkbox" id="resv_row6" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row7" type="checkbox" id="resv_row7" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row8" type="checkbox" id="resv_row8" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row9" type="checkbox" id="resv_row9" value="yes">
      </div></td>
    </tr>
    <tr class="room<?php echo $roomid%6;?>">
      <td><div align="center">
        <DIV align=center>18:30 ~ 19:30</DIV>
      </div></td>
      <td><div align="center">
        <DIV align=center>19:30 ~ 20:30</DIV>
      </div></td>
      <td><div align="center">
        <DIV align=center>20:30 ~ 21:30</DIV>
      </div></td>
      <td colspan="2" rowspan="2"><div align="center">
        <input name="Submit" type="submit" class="dingbatCopy" value="Submit">
        <input name="Reset" type="reset" class="dingbatCopy" value="Reset">
      </div>
        </td>
    </tr>
    <tr class="room<?php echo $roomid%6;?>">
      <td><div align="center">
        <input name="resv_row10" type="checkbox" id="resv_row10" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row11" type="checkbox" id="resv_row11" value="yes">
      </div></td>
      <td><div align="center">
        <input name="resv_row12" type="checkbox" id="resv_row12" value="yes">
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="year" value="<?php echo $year;?>">
  <input type="hidden" name="day" value="<?php echo $day;?>">
  <input type="hidden" name="month" value="<?php echo $month;?>">
  <input type="hidden" name="roomid" value="<?php echo $roomid;?>">
  <input type="hidden" name="roomnm" value="<?php echo $roomnm;?>">
</form>

<?php
		}//end is pclab
	}else{
?>
<div align="center">
<?php
		if($roomid==2){
			echo "�խY�n�w�ɷ|ĳ�U�A�Ь��t�줽�ǡ�";
		}else if($roomid>6){
			echo "�խY�n�w�ɡA�Шϥ� CS �H�c�b���K�X���J ��";
		}

		if($roomid==2){
			if(mktime(0,0,0,$tmonth,$tday+7,$tyear) < mktime(0,0,0,$month,$day,$year)){
				echo "<br>";
				echo "�շ|ĳ�U�u���w���@�P�����ɬq��";
			}
		}else if($roomid<7){
			if(mktime(0,0,0,$tmonth,$tday+14,$tyear) < mktime(0,0,0,$month,$day,$year)){
				echo "�լ�Q�ǥu���w����P�����ɬq��";
			}
		}
?>
</div>
<?php
	}
?>
</body>
</html>
