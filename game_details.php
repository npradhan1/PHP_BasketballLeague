<?php
	if(isset($_GET['dcode'])) {
			session_start(); 
			$_SESSION['dcode'] = $_GET['dcode'];
			$_SESSION['team_num'] = $_GET['team_num'];
			$_SESSION['coach'] = $_GET['coach'];
		}
	// connect to database
	require 'connect.php';
	
	$user_query = mysql_query("SELECT * FROM users");
	$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	$schedule = mysql_query("SELECT * FROM schedule ORDER BY gametime ASC") or die("Unable to connect to query");
	
	// variables
	$usercheck = false;	
	$dname = "";
	$gametime ="";
	$gym ="";
	$awayteam ="";
	$hometeam ="";
	$awayscore ="";
	$homescore ="";
	
	$dcode = $_SESSION['dcode'];
	$tno = $_SESSION['team_num'];
	$coach = $_SESSION['coach'];
	
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = true; // user already exists !!
			}
	}
	
	// check rows in the division
	while($row = mysql_fetch_array($fields)) {
				if($dcode== $row['dcode']){
					$dname = $row['dname'] ;
				}
			}

	// check rows in the schedule
	$num = 0;
	while($row = mysql_fetch_array($schedule)) {
			if($dcode== $row['dcode']){
				if(($tno==$row['awayteam'])|($tno==$row['hometeam'])){
					// convert time format to date
					$date = date_create($row['gametime']);
					$gametime[$num] = date_format($date, 'Y-m-d');;
					
					$gym[$num] = $row['gym'];
					
					// check ourscore, theirscore
					if($tno==$row['awayteam']){
						$ourscore[$num] = $row['awayscore'];
						$theirteam[$num] = $row['hometeam'];
						$theirscore[$num] = $row['homescore'];
					}
					else if($tno==$row['hometeam']){
						$ourscore[$num] = $row['homescore'];
						$theirteam[$num] = $row['awayteam'];
						$theirscore[$num] = $row['awayscore'];
					}
					if($ourscore[$num] == -1){
						$ourscore[$num] = "-";
						$theirscore[$num] = "-";
					}
					// convert team number into team name
					$team = mysql_query("SELECT * FROM team WHERE (tno = $theirteam[$num])&(dcode='{$_SESSION['dcode']}')") or die("Unable to connect to query");
					while($row = mysql_fetch_array($team)) {
						$theirteam[$num] = $row['coach'];
					}
					
					// check win or loss
					if($ourscore[$num] > $theirscore[$num]){
						$gameresult[$num] = "W";
					}
					else if($ourscore[$num] < $theirscore[$num]){
						$gameresult[$num] = "L";
					}
					else{
						$gameresult[$num] = "-";
					}
					
					$data[$num] = array('gametime' => $gametime[$num], 'gym' => $gym[$num], 'theirteam'=> $theirteam[$num]
						,'ourscore' => $ourscore[$num], 'theirscore' => $theirscore[$num], 'gameresult'=> $gameresult[$num]);
					$num++;
				}
			}
	}

	// array sort by win percent
	//array_multisort($gametime, SORT_ASC, $gym, SORT_ASC, $data);
			
	foreach ($data as $key => $row) {
				$gametime[$key] = $row['gametime'];
				$gym[$key] = $row['gym'];
				$theirteam[$key] = $row['theirteam'];
				$ourscore[$key] = $row['ourscore'];
				$theirscore[$key] = $row['theirscore'];
				$gameresult[$key] = $row['gameresult'];
				
				
				$show_list[$key]= "<tr height=\"25\"><td><div align=\"center\">$gametime[$key]</div></td>
						<td><div align=\"center\">$gym[$key]</div></td>
						<td><div align=\"center\">$theirteam[$key]</div></td>
						<td><div align=\"center\">$ourscore[$key]</div></td>
						<td><div align=\"center\">$theirscore[$key]</div></td>
						<td><div align=\"center\">$gameresult[$key]</div></td>
						</tr>";
			}
	
?>
<html>
<head>
<title>Game Schedule</title>
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

<tr><td style="border-bottom: solid 1px gray;">&nbsp;</td>
</table>
<table align=center width="500">
<tr height=30>
<td>Division Name: <strong>
<?php
		echo $dname;
?></strong> &emsp; Team Name:  <strong>
<?php
		echo $coach;
?></strong>    
</td>
</tr></table>


<table align=center width="500" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td><div align="center">DATE</div></td>
<td><div align="center">GYM</div></td>
<td><div align="center">VS</div></td>
<td width="40"><div align="center">O-S</div></td>
<td width="40"><div align="center">T-S</div></td>
<td width="40"><div align="center">W/L</div></td>
</tr>
<?php
	$num = 0;
	while ($num < count($data)){
			echo $show_list[$num];
			$num++;
		}
?>

</table>

<table width="500" align=center border=0>
<tr><td height="50" colspan="2">
      <div align="right">
	  <input type="button" style="background-color:white; color:black;" onclick="history.back();" value="Back">
		</input>
        </div></td>
</tr>
<tr>
  <td><span class="style2"> Glossary</span></td>
</tr>
<tr>
<td>
  <span class="style6"><strong>O-S</strong>: Our Score &emsp;
  <strong>T-S</strong>: Their Score &emsp;
  <strong>W/L</strong>: Win/Loss &emsp;
  </span></td>
</tr>
</table>

</body>
</html>