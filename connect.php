<?php
	
	$dbHost = "localhost";
	$dbUser = "slee172";
	$dbPass = "1webdb23";
	$dbname = "slee172";
	$dbconnect = mysql_connect($dbHost,$dbUser,$dbPass)	or die("Unable to connect to MySQL");
	mysql_select_db($dbname,$dbconnect);
?>
