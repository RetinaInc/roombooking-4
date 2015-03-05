<?php
        $monthName=array("一","二","三","四","五","六","七","八","九","十","十一","十二");
        $weekdayName=array("日","一","二","三","四","五","六");
        $rownames=array('08:00 ~ 09:00','09:00 ~ 10:00','10:00 ~ 11:00','11:00 ~ 12:00',
                        '12:00 ~ 13:30','13:30 ~ 14:30','14:30 ~ 15:30','15:30 ~ 16:30',
                        '16:30 ~ 17:30','17:30 ~ 18:30','18:30 ~ 19:30','19:30 ~ 20:30','20:30 ~ 21:30');


	$link=include("inc/dbcon.inc.php");

	$dateinformat = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")));

	$query="SELECT rq.*, rm.name as roomnm FROM resv_rqst as rq INNER JOIN room as rm ON rq.room_id = rm.id
		where resv_date = '$dateinformat' AND cancel = 'no' ORDER BY rq.applicant, rq.room_id, rq.resv_row";

	$result=mysql_query($query);

	if(mysql_num_rows($result)==0){
		exit;
	}

	while($rsdata=mysql_fetch_array($result)){
		$maillist[$rsdata['applicant']]=$rsdata['email'];
		$resvinfo[$rsdata['applicant']].="$dateinformat ".$rownames[$rsdata['resv_row']]." ".$rsdata['roomnm']."\n";
	}

	$namelist=array_keys($maillist);

	foreach ( $namelist as $applicant ){

		$msg ="親愛的 ".$applicant." 您好:\n\n";
		$msg.="您預約了以下時段的 研討室/PC Lab, \n\n";
		$msg.=$resvinfo[$applicant];
		$msg.="\n";
		$msg.="請您確認您的使用狀況！\n";
		$msg.="若有異動，煩請您上線修改: http://www.csie.nctu.edu.tw/roombooking\n\n";
		$msg.="感謝您！\n\n";
		$msg.="資工系計算機中心　敬上";

		mail($maillist[$applicant], "[交大資工系計中]研討室預約通知", $msg, 
		     "From: 交大資工系計中 <wwwTA@csie.nctu.edu.tw>");
	}
?>
