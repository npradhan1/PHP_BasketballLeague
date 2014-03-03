<?php
	// connect to database
	require 'connect.php';
	session_start(); 
	if(isset($_GET['commissioner'])) {
			
			$_SESSION['commissioner'] = $_GET['commissioner'];
	}
	// variables
	$dcode = "";
	$dname = "";
	$usercheck = "false";
	$userid = "";
	$usertype = 0;	
	$tno = 0;

		
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
	
	$list_query = mysql_query("SELECT * FROM users WHERE usertype=2");
	
	while($row = mysql_fetch_array($list_query)) {
		if($_SESSION['commissioner'] == $row['userid']){
					$commissioner = $row['userid'];
					$dcode = $row['divisionCode'];
					
					$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
					while($row = mysql_fetch_array($fields)) {
						if ($dcode == $row['dcode']){
							$dname = $row['dname'];
						}
					}
		}
	}
		
	if($_SERVER['REQUEST_METHOD']=='POST'){
	 	//$coach = $_POST['coach'];
		
		$commissionerid = $_POST['commissionerid'];
		$passwd = $_POST['passwd'];
		$dcode = $_POST['division'];
		
		$error_arr = array();
		
		if($dcode =="None"){
                $error_arr['tno'] = 'Please put correct division name';
        }
		else if($commissionerid ==""){
                $error_arr['tno'] = 'Please put correct commissioner id';
        }
		else if(preg_match('/[[:space:]]/',$commissionerid)){
                $error_arr['tno'] = 'Add Error Please do not use the space between words';
        }
		else if($passwd ==""){
                $error_arr['tno'] = 'Please put correct commissioner password';
        }
		else{
				
		}
		
		if(count($error_arr) == 0){
			$_SESSION['dcode'] = $dcode;
			$_SESSION['commissionerid'] = $commissionerid;
						
			$_SESSION['passwd'] = $passwd;
			$_SESSION['usertype'] = 2;

			mysql_query("INSERT INTO users (userid, password, usertype, divisionCode)
				VALUES ('{$_SESSION['commissionerid']}','{$_SESSION['passwd']}','{$_SESSION['usertype']}','{$_SESSION['dcode']}')");
				header('location: add_commissioner.php');		
		}
	}
?>
<html>
<head>
<title>Add Page</title>
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
<table align=center width="500" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td width="100"><div align="center">DIVISION</div></td>
<td width="150"><div align="center">COMMISSIONER ID</div></td>
<td width="150"><div align="center">COMMISSIONER PASSWD</div></td>
</tr>
<tr height="25"><td>
	<select name="division" id ="division" onChange="divisionselected(this)">;
	<option value="None">None</option>;
<?php
 		$division_query = mysql_query("SELECT * FROM division");
        while($row = mysql_fetch_array($division_query)) {
		//$dcode = $row['dcode'];
				if($usertype == 1){
                	echo '<option value="'.$row['dcode'].'">'.$row['dcode'].", ".$row['dname'].'</option>'; 
				 }
				 else {
				 	if($divisionCode == $row['dcode']){
					 	echo '<option value="'.$row['dcode'].'">'.$row['dcode'].", ".$row['dname'].'</option>'; 
					 }
				 }
        }
    echo '</select>';		
?>
</td>
<td><div align="center"><input type="text" name="commissionerid" id="commissionerid" value="<?php echo $commissionerid;?>" maxlength="15" size="15"/></div></td>
<td><div align="center">
<input type="text" name="passwd" id="passwd" value="<?php echo $passwd;?>" maxlength="25" size="10"/></div></td>

</tr>
</table>

<table align=center width="500"><tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['tno'];?>
</td></tr><tr><td align="right"><input type="button"  onClick="location.href='add_commissioner.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Submit" style="background-color:white; color:black;"></input></td></tr>
</form>
</table>
</body>
</html>