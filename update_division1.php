<?php
	// connect to database
	require 'connect.php';
	session_start(); 
	if(isset($_GET['dcode'])) {
			
			$_SESSION['dcode'] = $_GET['dcode'];
	}
	// variables
	$dcode = "";
	$dname = "";
	$usercheck = "false";
	$userid = "";
	$usertype = 0;	

		
	$user_query = mysql_query("SELECT * FROM users");
	$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	$schedule = mysql_query("SELECT * FROM schedule ORDER BY gametime ASC") or die("Unable to connect to query");
	$team = mysql_query("SELECT * FROM team ORDER BY coach ASC") or die("Unable to connect to query");
	
	
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = "true"; // user already exists !!
				$userid = $row['userid'];
				$usertype = $row['usertype'];
				$divisionCode = $row['divisionCode'];
			}
	}
	
	$list_query = mysql_query("SELECT * FROM division WHERE dcode='{$_SESSION['dcode']}'");
	
	while($row = mysql_fetch_array($list_query)) {
		
					$dname = $row['dname'];
					$dcode = $row['dcode'];
	}
		
	if($_SERVER['REQUEST_METHOD']=='POST'){
	 	//$coach = $_POST['coach'];
		//$tno = $_POST['tno'];
		$dname = $_POST['dname'];
		
		
		
		//$_SESSION['dcode'] = $divisionCode;
		//$_SESSION['userid'] = $userid;
		//$_SESSION['tno'] = $valid_arr['tno'];
		$_SESSION['dname'] = $dname;
		
		mysql_query("UPDATE division SET dname = '{$_SESSION['dname']}'
		 where dcode ='{$_SESSION['dcode']}'");
		header('location: update_division.php');		
	
	}

?>
<html>
<head>
<title>Update Page</title>
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
		echo ($usercheck == "true" ? "You are logged in as '{$_SESSION['userID']}' &nbsp; <a href=\"admin.php\">Admin<a> &nbsp; <a href=\"logout.php\">Logout<a>" : "<p>Login to register for the basketball 2012-2013 season. <a href=\"login.php\">Login</a> </p>"); 
	?> 
	</td>
	</tr>
<tr><td>&nbsp;</td>
</tr>
<tr><td align="center" style="color:black; font-size: 15px;" >
	<a href="main.php">Game Schedule</a> &emsp;|&emsp; 
			           <a href="division_listing.php">League Standings</a>
</td></tr>

<tr><td style="border-bottom: solid 1px gray;">&nbsp;</td>
</tr>
<tr><td>&nbsp;</td></tr>


<tr><td> Hello, <strong><?php echo $_SESSION['userID'];?></strong>&nbsp;&nbsp; You're Permission is &nbsp;
<strong>
<?php
	if($usertype == 1){
		echo "DIRECTOR-ADMIN";
	}
	else if($usertype == 2){
		echo "COMMISSIONER-ADMIN";
	}
	else if($usertype == 3){
		echo "COACH-ADMIN";
	}
?></strong>
</td></tr>
</table>
<tr><td>&nbsp;</td></tr>

<form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
<table align=center width="350" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td width="100"><div align="center">DIVISION CODE</div></td>
<td width="150"><div align="center">DIVISION NAME</div></td>
</tr>
<tr height="25"><td><div align="center"><?php echo $dcode;?></div></td>
<td><div align="center">
<input type="text" name="dname" id="dname" value="<?php echo $dname;?>" maxlength="25" size="25"/></div></td>

</tr>
</table>

<table align=center width="350"><tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['tno'];?>
</td></tr><tr><td align="right"><input type="button"  onClick="location.href='update_division.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Update" style="background-color:white; color:black;"></input></td></tr>
</form>
</table>
</body>
</html>