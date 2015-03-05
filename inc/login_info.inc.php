<?php

$table_fg_color;//#xxyyzz format
$table_bg_color="#c4c4c4";

$users_fields = array('room_user_id' => '使用者'
                    ,'passwd' => '密碼'
                    ,'user_group' => '使用者群組'
                    ,'ch_name' => '中文名字'
              );


$group_name = array('ta' => 'TA'
		,'admin' => 'Admin'
		,'user' => 'User'
		);

function forbidden()
{
  	echo '<center><h4>有任何問題,請洽系計中助教</h4></center>';
}
	
function authenticate()
{
    $_SESSION['authenticated']=1;
    header('WWW-Authenticate: Basic realm="CSIE"');
    header('HTTP/1.0 401 Unauthorized');
    forbidden();
    exit;
}

function logout_button()
{
	print "<form action=\"logout.php\" method=POST>";
	print "<input type=submit ></form>";
}

function logout($mesg)
{
	header('HTTP/1.0 401 Unauthorized');
	session_start();
  	session_unregister('user_group');
  	session_unregister('print_user_id');

	unset ($_SERVER['PHP_AUTH_USER'],
				 $_SERVER['PHP_AUTH_PW']);
	session_unset();
	session_destroy();
	//debug
	//print "$HTTP_SSESSION_VARS['room_user_id']";
	print "<center><h3>$mesg</h3></center>";
}

?>
