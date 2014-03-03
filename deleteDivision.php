<?php
	require 'connect.php';

	if(isset($_GET['dcode'])){
		$dcode = $_GET['dcode'];
		$sql_query = "delete from division where dcode = '$dcode'";
		$res = mysql_query($sql_query);
		$row = mysql_fetch_array($res);
		header('Location: index.php');
	}
	


?>