<?php
	        include("inc/login_info.inc.php");
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		            forbidden();
		            exit;
		}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�L���D���</title>
<style type="text/css">
<!--
.style4 {color: #666666}
.dingbatCopy {
	font-family: Arial, Helvetica, sans-serif;
 	color: #33EE11; font-weight: bolder; font-size: medium;
	font-size: 28px;
}
.dingbatCopy1 {
	font-family: Arial, Helvetica, sans-serif;
 	color: #660000; font-weight: bolder; font-size: medium;
}
.legal {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	color: #333333;
}
a{
	text-decoration: none;
	color: #FFFFFF;
}
.room6 {
	font-size: 20px;
        font-weight: 500;
        color: #FFFFFF;
}
-->
</style>
</head>

<body bgcolor="#456789">
<br>
<div align="center" class="dingbatCopy" >
��j��u��Q�Ǻ[�q���ЫǺ޲z�t��
</div>
  <hr>
  <br>
  <table width="200" border="0" align="center">
    <tr>
      <td ><ul>
        <li class="room6"><a href="main.php">��Q�ǹw��</a></li>
      </ul></td>
    </tr>
    <tr>
      <td><ul>
        <li class="room6"><a href="main2.php">�q���Ыǹw��</a></li>
      </ul></td>
    </tr>
</table>
  <br>
  <hr>
  <p align="center" class="dingbatCopy1">�ϥΧ��аȥ������s�����n�X</p>
  <p align="center" class="legal"><a href="mailto:jasonlin@csie.nctu.edu.tw" ><�Y��������D�A�Ь��t�p���U��></a></p>

</body>
</html>
