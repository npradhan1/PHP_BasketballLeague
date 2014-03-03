<?php
	// connect to database
	require 'connect.php';
	
	session_start();

	if(isset($_GET['userid'])) {
			//$_SESSION['dcode'] = $_SESSION['game_division'];
			$_SESSION['userID'] = $_GET['userid'];
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
	$divisionCode="";	
	$tno = 0;
	$isdivision= "false";

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

	while($row = mysql_fetch_array($team)) {
			if($usertype == 3){
				if($userid == $row['coach']){
					$tno = $row['tno'];
				}
			}
	}					
	
	$num = 0;
	while($row = mysql_fetch_array($schedule)) {
		
		if($usertype == 2){
			if($divisionCode == $row['dcode']){
					// convert time format to date
					$date = date_create($row['gametime']);
					$gamedate[$num] = date_format($date, 'Y-m-d');;
					$gametime[$num] = date_format($date, 'H:i');
					
					$gym[$num] = $row['gym'];
					$gid[$num] = $row['gid'];
					$dcode[$num] = $row['dcode'];
					
					$hometeam[$num] = $row['hometeam'];
					$awayteam[$num] = $row['awayteam'];
					$awayscore[$num] = $row['awayscore'];
					$homescore[$num] = $row['homescore'];
				
					if($homescore[$num] == -1){
						$homescore[$num] = "-";
						$awayscore[$num] = "-";
					}
										
					// convert team number into team name
					$team = mysql_query("SELECT * FROM team WHERE dcode='$dcode[$num]'") or die("Unable to connect to query");
					while($row = mysql_fetch_array($team)) {
						if($row['tno'] == $hometeam[$num]){
								$hometeam[$num] = $row['coach'];
						}
						else if($row['tno'] == $awayteam[$num]){
							   $awayteam[$num] = $row['coach'];
						}
					}
					
					$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
					while($row = mysql_fetch_array($fields)) {
						if ($dcode[$num] == $row['dcode']){
							$dname[$num] = $row['dname'];
						}
					}
					
					$data[$num] = array('dcode' => $dcode[$num], 'dname' => $dname[$num], 'gid' => $gid[$num], 'gamedate' => $gamedate[$num], 'gametime' => $gametime[$num], 'gym' => $gym[$num], 'awayteam'=> $awayteam[$num], 'hometeam'=> $hometeam[$num]
						,'awayscore' => $awayscore[$num], 'homescore' => $homescore[$num]);
					$num++;
				}
		}
		else if($usertype == 1){
			
					// convert time format to date
					$date = date_create($row['gametime']);
					$gamedate[$num] = date_format($date, 'Y-m-d');;
					$gametime[$num] = date_format($date, 'H:i');
					
					$gym[$num] = $row['gym'];
					$gid[$num] = $row['gid'];
					$dcode[$num] = $row['dcode'];
					
										
					$hometeam[$num] = $row['hometeam'];
					$awayteam[$num] = $row['awayteam'];
					$awayscore[$num] = $row['awayscore'];
					$homescore[$num] = $row['homescore'];
				
					if($homescore[$num] == -1){
						$homescore[$num] = "-";
						$awayscore[$num] = "-";
					}
										
					// convert team number into team name
					$team = mysql_query("SELECT * FROM team WHERE dcode='$dcode[$num]'") or die("Unable to connect to query");
					while($row = mysql_fetch_array($team)) {
						if($row['tno'] == $hometeam[$num]){
								$hometeam[$num] = $row['coach'];
						}
						else if($row['tno'] == $awayteam[$num]){
							   $awayteam[$num] = $row['coach'];
						}
					}
					
					$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
					while($row = mysql_fetch_array($fields)) {
						if ($dcode[$num] == $row['dcode']){
							$dname[$num] = $row['dname'];
						}
					}
					
					$data[$num] = array('dcode' => $dcode[$num], 'dname' => $dname[$num],'gid' => $gid[$num], 'gamedate' => $gamedate[$num], 'gametime' => $gametime[$num], 'gym' => $gym[$num], 'awayteam'=> $awayteam[$num], 'hometeam'=> $hometeam[$num]
						,'awayscore' => $awayscore[$num], 'homescore' => $homescore[$num]);
					$num++;
				}
		
	}
	// array sort by win percent
	if($isdivision== "true"){
		array_multisort($gamedate, SORT_ASC, $gym, SORT_ASC, $data);
	}
			
	foreach ($data as $key => $row) {
				
				$dcode[$key] = $row['dcode'];
				$dname[$key] = $row['dname'];
				
				$gid[$key] = $row['gid'];
				$gamedate[$key] = $row['gamedate'];
				$gametime[$key] = $row['gametime'];
				$gym[$key] = $row['gym'];
				$awayteam[$key] = $row['awayteam'];
				$hometeam[$key] = $row['hometeam'];
				$homescore[$key] = $row['homescore'];
				$awayscore[$key] = $row['awayscore'];
				//&&gid=$gid[$key] dcode=$_SESSION['game_division']
				$show_list[$key]= "<tr height=\"25\"><td><div align=\"center\">$gamedate[$key]</div></td>
						<td><div align=\"center\">$gametime[$key]</div></td>
						<td><div align=\"center\">$dname[$key]</div></td>
						<td><div align=\"center\">$gid[$key]</div></td>
						<td><div align=\"center\">$awayteam[$key] &nbsp; vs &nbsp; $hometeam[$key]</a></div></td>
						<td><div align=\"center\">$gym[$key]</div></td>
						</tr>";
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
<table align=center width="650">
<tr><td align="right"><input type="button" onClick="location.href='admin.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="button" onClick="location.href='add_schedule1.php?userid=<?php echo $_SESSION['userID']?>'" value="Add" style="background-color:white; color:black;"></input>
</td></tr>
</table>
<form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
<table align=center width="650" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td><div align="center">DATE</div></td>
<td><div align="center">TIME</div></td>
<td><div align="center">DIVISION</div></td>
<td><div align="center">GAME ID</div></td>
<td><div align="center">Away vs Home</div></td>
<td width="40"><div align="center">GYM</div></td>

</tr>
<?php
	$num = 0;
	while ($num < count($data)){
			echo $show_list[$num];
			$num++;
		}
?>
</table>
</form>
</body>
</html>