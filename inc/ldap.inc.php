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
	    ���b���K�X, ���ҬO�_���T, return true or false
		�K�X�O unix ���K�X, �D nt �K�X	
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
		���b��, �ݬO�_���o�ӤH, return true or false
	*/
	function ldap_is_user_existed( $uid)
	{
		if( is_array(ldap_get_user_data($uid) ) )
			return true;
		return false;
	}
	/*
	    ���b��, �Y�S���w $attr, �h�^�Ǥ@�}�C�]�t uid, sn, givenname, mail
		�`�N!! �s���覡�� $user["uid"][0], �᭱�n�[�W [0]
		Ex: $user = ldap_get_user_data( "id" );
			echo $user["uid"][0];
		�Y�] $attr="*", �h�|���o�ӨϥΪ̩Ҧ������
		�Y�u�Q���X��, �n�� $attr ��}�C, ��J�A�n���ݩʦW��
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
		���b��, �^�Ǥ@�}�C�]�t�ӱb������ܪ��ݩ�
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
		���b��, ���ҬO�_ta, return true or false
	*/
	function ldap_is_ta($uid)
	{
		return ldap_is_in_group( $uid, "security" );
	}
	/*
		���b����s��, ���ҬO�_�b�Ӹs�դ�
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
