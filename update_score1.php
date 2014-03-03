<?php
	// connect to database
	require 'connect.php';
	if(isset($_GET['gid'])) {
			session_start(); 
			$_SESSION['dcode'] = $_GET['dcode'];
			$_SESSION['gid'] = $_GET['gid'];
	}
	// variables
	$dcode = "";
	$dname = "";
	$temptime ="";
	$temp = "";
	$num = 0;
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
			}
	}
	
	while($row = mysql_fetch_array($team)) {
			if($usertype == 3){
				if($userid == $row['coach']){
					$tno = $row['tno'];
				}
			}
	}					
	
	
	while($row = mysql_fetch_array($schedule)) {
		if($_SESSION['dcode']==$row['dcode']){
				if($_SESSION['gid']==$row['gid']){
					// convert time format to date
					$date = date_create($row['gametime']);
					$gametime = date_format($date, 'Y-m-d');;
					$dcode = $row['dcode'];
					$gym = $row['gym'];
					
					$hometeam = $row['hometeam'];
					$awayteam = $row['awayteam'];
					$awayscore = $row['awayscore'];
					$homescore = $row['homescore'];
				
					if($homescore == -1){
						$homescore = "-";
						$awayscore = "-";
					}
										
					// convert team number into team name
					$team = mysql_query("SELECT * FROM team") or die("Unable to connect to query");
					
					while($row = mysql_fetch_array($team)) {
						if ($_SESSION['dcode'] == $row['dcode']){
							if($row['tno'] == $hometeam){
									$hometeam = $row['coach'];
							}
							else if($row['tno'] == $awayteam){
								   $awayteam = $row['coach'];
							}
						}
					}
					
					
				}
		}
	}
	
	
	if($_SERVER['REQUEST_METHOD']=='POST'){
	 	$awayscore = $_POST['awayscore'];
		$homescore = $_POST['homescore'];
		
		$error_arr = array();
		
		if(!preg_match('/^[0-9]+$/',$awayscore)){
                $error_arr['awayscore'] = 'Please put correct score';
        }
		else if (!preg_match('/^[0-9]+$/',$homescore)){
			 	$error_arr['homescore'] = 'Please put correct score';
				
		}
		else if($homescore == $awayscore){
                $error_arr['awayscore'] = 'the score can not be tied';
		}
		else{
				$valid_arr['awayscore'] = $awayscore;
				$valid_arr['homescore'] = $homescore;
		}
		
		if(count($error_arr) == 0){
			$_SESSION['awayscore'] = $valid_arr['awayscore'];
			$_SESSION['homescore'] = $valid_arr['homescore'];
			mysql_query("UPDATE schedule SET awayscore = '{$_SESSION['awayscore']}', homescore = '{$_SESSION['homescore']}'
			 where (dcode='{$_SESSION['dcode']}')&&(gid='{$_SESSION['gid']}')");
			header('location: update_score.php');		
		}
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
<table align=center width="500" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td><div align="center">DATE</div></td>
<td><div align="center">GYM</div></td>
<td><div align="center">Away vs Home</div></td>
<td width="40"><div align="center">A-Score</div></td>
<td width="40"><div align="center">H-Score</div></td>
</tr>
<tr height="25"><td><div align="center"><?php echo $gametime;?></div></td>
<td><div align="center"><?php echo $gym;?></div></td>
<td><div align="center"><?php echo $awayteam;?> &nbsp; vs &nbsp; <?php echo $hometeam;?></a></div></td>
<td><div align="center">						
<input type="text" name="awayscore" id="awayscore" value="<?php echo $awayscore;?>" maxlength="3" size="2"/></div></td>
<td><div align="center">
<input type="text" name="homescore" id="homescore" value="<?php echo $homescore;?>" maxlength="3" size="2"/></div></td>
</tr>
</table>

<table align=center width="500"><tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['awayscore'];?><?php echo $error_arr['homescore'];?>
</td></tr><tr><td align="right"><input type="button" onClick="location.href='update_score.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Update" style="background-color:white; color:black;"></input></td></tr>
</form>
</table>
</body>
</html>