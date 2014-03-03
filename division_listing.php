<?php
	// connect to database
	require 'connect.php';
	
	$user_query = mysql_query("SELECT * FROM users");
	$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	
	// variables
	$usercheck = false;	
	$dcode = "";
	$dname = "";
	
	// check user id
	session_start(); 
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = true; // user already exists !!
			}
	}
	
	// check divisions
	while($row = mysql_fetch_array($fields)) {
				$dcode = $row['dcode'];
				$dname = $row['dname'] ;
				
				$show_dname .= "<a href=\"standing.php?dcode=$dcode\" <strong>$dname</strong></a><br><br>";
			}
	
?>
<html>
<head>
<title>Division Listing</title>
<style type="text/css">
td {align: left; color: black; font-size: 12px;}
.style1 {
	font-size: 18px;
	font-weight: bold;
}
</style>
</head>
<body bgcolor="white" style="color:white">
<table align=center border=0>

<tr>
    <td align="center"><span class="style1">2012-13 East Marietta Basketball </span></td>
</tr>	
<tr>
    <td align="center"><align="left" style="color:black; font-size: 12px;" >
    <?php
		echo ($usercheck == true ? "You are logged in as '{$_SESSION['userID']}' &nbsp; <a href=\"admin.php\">Admin<a> &nbsp; <a href=\"logout.php\">Logout<a>" : "<p>Login to register for the basketball 2012-2013 season. <a href=\"login.php\">Login</a> </p>"); 
	?> 
	</td>
	</tr>
<tr><td>&nbsp;</td>
</tr>
<tr><td align="center" style="color:black; font-size: 15px;" >
	<a href="main.php">Game Schedule</a> &emsp;|&emsp; 
			           <a href="division_listing.php">League Standings</a>
</td></tr>
<tr><td>&nbsp;</td>
</tr>
<tr>
<td><fieldset>
<legend>DIVISION</legend>
<?php
		echo $show_dname;
?>
</fieldset>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
</table>

</body>
</html>