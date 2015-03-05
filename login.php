<?php
/*
 *  using pop3 authentication
 */
header("Cache-Control: no-store, no-cache, must-revalidate");
include_once("inc/login_info.inc.php");
include_once("inc/lib.inc.php");

session_start();

  if (!isset($_SERVER['PHP_AUTH_USER']) || !($_SESSION['authenticated']==1) ) {
		authenticate();
  } 
  else 
  {

	//authentication
	if (verify_account($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])){
//	if (verify_old($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])){
		if(!ista($_SERVER['PHP_AUTH_USER'])){
			authenticate();
		}else{
  			$_SESSION['room_user_id'] = $_SERVER['PHP_AUTH_USER'];
			header("Location: https://".$_SERVER['HTTP_HOST']
		       		.dirname($_SERVER['PHP_SELF'])
		       		."/"."index.php");
		}
	}
	else
	{
		authenticate();
	}
  }
?>

