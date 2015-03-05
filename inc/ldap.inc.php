<?

	/*
		Configure for LDAP Server
	*/
	$LDAP_SERVER = "ldaps://ldapmaster.cs.nctu.edu.tw";
	$LDAP_ROOT_DN = "dc=cs,dc=nctu,dc=edu,dc=tw";

	$LDAP_CN_MANAGER_DN = "cn=Manager,{$LDAP_ROOT_DN}";
	$LDAP_OU_PEOPLE_DN = "ou=People,{$LDAP_ROOT_DN}";
	$LDAP_OU_GROUP_DN = "ou=Group,{$LDAP_ROOT_DN}";

	function ldap_connect_server( $server='' )
	{
		global $LDAP_SERVER;
		if( empty($server) )
			$server = $LDAP_SERVER;
		$ldap_connect_id = @ldap_connect($LDAP_SERVER);
		if( !$ldap_connect_id )
	        die("Connect LDAP Server Fail!\n");
		@ldap_set_option( $ldap_connect_id, LDAP_OPT_PROTOCOL_VERSION, 3);
		return $ldap_connect_id;
	}	
	/*
	    給帳號密碼, 驗證是否正確, return true or false
		密碼是 unix 的密碼, 非 nt 密碼	
	*/
	function ldap_login_user( $uid, $pass)
	{
		global $LDAP_OU_PEOPLE_DN;
		$ldap_connect_id = ldap_connect_server();
		$ldap_query = "uid={$uid},".$LDAP_OU_PEOPLE_DN;
		$login_result = @ldap_bind( $ldap_connect_id, $ldap_query, $pass);
		@ldap_close($ldap_connect_id);
		return $login_result;
	}
	/*
		給帳號, 看是否有這個人, return true or false
	*/
	function ldap_is_user_existed( $uid)
	{
		if( is_array(ldap_get_user_data($uid) ) )
			return true;
		return false;
	}
	/*
	    給帳號, 若沒指定 $attr, 則回傳一陣列包含 uid, sn, givenname, mail
		注意!! 存取方式為 $user["uid"][0], 後面要加上 [0]
		Ex: $user = ldap_get_user_data( "id" );
			echo $user["uid"][0];
		若設 $attr="*", 則會取得該使用者所有的資料
		若只想取幾個, 要把 $attr 當陣列, 放入你要的屬性名稱
		Ex: $attr = array( "sn", "mail")
	*/
	function ldap_get_user_data( $uid, $attr='')
	{
		global $LDAP_OU_PEOPLE_DN;

		$ldap_connect_id = ldap_connect_server();
		$ldap_filter = "(&(uid={$uid})(sn={$uid}))";
		if( !is_array($attr) )
		{
			if( empty( $attr) )
				$attr = array( "uid", "sn", "givenname", "mail");
			else if( $attr=="*" )
				$attr = array();
		}
		$search_id = @ldap_search( $ldap_connect_id, "uid={$uid},".$LDAP_OU_PEOPLE_DN, $ldap_filter,$attr);
	
	    if(!$search_id)
        	return -1;
		
		$info = @ldap_get_entries( $ldap_connect_id, $search_id);
		@ldap_close($ldap_connect_id);
        if( $info["count"]==1 )
            return $info[0];
        else
            return 0;
	}
	/*
		給帳號, 回傳一陣列包含該帳號能顯示的屬性
	*/
	function ldap_list_user_attr( $uid)
	{
		$user = ldap_get_user_data( $uid, "*" );
		while( $d=each($user) )
		{
			if( !is_integer($d[0]) ) 
				echo "{$d[0]}\n";
		}
	}
	/*
		給帳號, 驗證是否ta, return true or false
	*/
	function ldap_is_ta($uid)
	{
		return ldap_is_in_group( $uid, "security" );
	}
	/*
		給帳號跟群組, 驗證是否在該群組內
	*/
	function ldap_is_in_group( $uid, $group)
	{
		global $LDAP_OU_GROUP_DN;
		$ldap_connect_id = ldap_connect_server();
		$ldap_filter = "cn={$group}";
		$justthese = array("memberuid"); 
		$query = $LDAP_OU_GROUP_DN;
		$search_id= @ldap_search( $ldap_connect_id, $query, $ldap_filter,$justthese);
		if(!$search_id)
			return false;
			
        $info = @ldap_get_entries( $ldap_connect_id, $search_id);
		
        @ldap_close($ldap_connect_id);
        if( $info["count"]>0 )
		{
            if( is_numeric(array_search( $uid, $info[0]["memberuid"]) ) )
				return true;
		}
        return false;
		
	}
?>
