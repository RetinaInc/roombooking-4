<?php
        $monthName=array("�@","�G","�T","�|","��","��","�C","�K","�E","�Q","�Q�@","�Q�G");
        $weekdayName=array("��","�@","�G","�T","�|","��","��");
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

		$msg ="�˷R�� ".$applicant." �z�n:\n\n";
		$msg.="�z�w���F�H�U�ɬq�� ��Q��/PC Lab, \n\n";
		$msg.=$resvinfo[$applicant];
		$msg.="\n";
		$msg.="�бz�T�{�z���ϥΪ��p�I\n";
		$msg.="�Y�����ʡA�нбz�W�u�ק�: http://www.csie.nctu.edu.tw/roombooking\n\n";
		$msg.="�P�±z�I\n\n";
		$msg.="��u�t�p������ߡ@�q�W";

		mail($maillist[$applicant], "[��j��u�t�p��]��Q�ǹw���q��", $msg, 
		     "From: ��j��u�t�p�� <wwwTA@csie.nctu.edu.tw>");
	}
?>
