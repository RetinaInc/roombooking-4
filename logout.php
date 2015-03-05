<?php
/*
 *  using pop3 authentication
 */
	header("Cache-Control: no-store, no-cache, must-revalidate");

	session_start();
  	//unset($_SERVER['PHP_AUTH_USER']);
// Unset all of the session variables.
	$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()])) {
   		setcookie(session_name(), '', time()-42000, '/');
	}

// Finally, destroy the session.
	session_destroy();

	header("Location: https://".$_SERVER['HTTP_HOST']
	       .dirname($_SERVER['PHP_SELF'])
	       ."/"."index.php");
?>

