<?php
function verify_account($id, $passwd)
{
	/*
         * use @veryfi_account to suppress the error message 
	 */
	if(empty($id) || empty($passwd)){
		return false;
	}
	$group=getgroup($id);
	if($group=="no" && !ista($id)){
		return false;
	}
        $mbox = imap_open ("{csmail.cs.nctu.edu.tw:995/pop3/ssl/novalidate-cert}", "$id", "$passwd");
        //$mbox = imap_open ("{pop3.csie.nctu.edu.tw:995/pop3/ssl}", "$id", "$passwd");
        if ($mbox == false){
                return false;
	}

        imap_close($mbox);
        return true;
}

function verify_old($id,$passwd)
{
	$dblink=include("userdb.inc.php");
	$query = "SELECT user_id, passwd  from users where user_id='$id'";
	$result = mysql_query($query) or die("§A¼g¤°»ò³¾query!");
	$tmp_line = mysql_fetch_array($result, MYSQL_ASSOC);
	mysql_free_result($result);
	mysql_close($dblink);
	if($tmp_line==""){
		echo "here";
		return false;
	}else{
		echo "<br>";
		echo md5($tmppasswd);
		echo "<br>";
		echo $tmppasswd;
		$tmppasswd=$tmp_line['passwd'];
		if(md5($passwd)==$tmppasswd){
			return true;
		}else{
			return false;
		}
	}
	return false;
}
function getgroup_old($id)
{
	$dblink=include("dbcon.inc.php");
	$query="SELECT room_user_group from users where room_user_id = '$id'";
	$result = mysql_query($query);
	list($group)=mysql_fetch_row($result);
	mysql_free_result($result);
	mysql_close($dblink);
	return $group;
}

function getgroup($id)
{
	return "gcp";
/*
	$command = "getgroup.sh ".$id;
	system($command,$rev);
	switch ($rev){
		case 0:
			return "faculty";
		case 1:
			return "gcp";
		case 2:
		default:
			return "no";
	}
*/
}
function ista($id)
{
	return true;
/*
	$command = "checkta.sh ".$id;
	system($command,$rev);
	if($rev==0){
		return true;
	}else{
		return false;
	}
*/
}
function isstaff($id)
{
	return true;
/*
	$command = "checkstaff.sh ".$id;
	system($command,$rev);
	if($rev==0){
		return true;
	}else{
		return false;
	}
*/
}
?>
