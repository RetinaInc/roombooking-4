<?php
	$link=mysql_pconnect("xxxx","xxxx","xxxx") or die("db server connection error!");
	mysql_select_db("roombooking",$link) or die("can't select database!");
	return $link;
?>
