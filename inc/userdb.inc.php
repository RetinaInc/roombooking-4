<?php
	$lalink=mysql_pconnect("xxxx","xxxx","xxxx") or die("db server connection error!");
	mysql_select_db("print",$lalink) or die("can't select database!");
	return $lalink;
?>
