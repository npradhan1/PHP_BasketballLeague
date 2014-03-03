<?php
	if(isset($_GET['dcode'])) {
			session_start(); 
			$_SESSION['dcode'] = $_GET['dcode'];
	}
	// connect to database
	require 'connect.php';
	
	$user_query = mysql_query("SELECT * FROM users");
	$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	$schedule = mysql_query("SELECT * FROM schedule") or die("Unable to connect to query");
	$team = mysql_query("SELECT * FROM team ORDER BY coach ASC") or die("Unable to connect to query");
	
	// variables
	$usercheck = false;	
	$dcode = $_SESSION['dcode'];
	$dname = "";
	$gametime ="";
	$gym ="";
	$awayteam ="";
	$hometeam ="";
	$awayscore ="";
	$homescore ="";
	$MaxNum = 100; // Maximum number of team
	$i = 0;
	while ($i < $MaxNum){
			$wins_home[$i] = 0;
			$wins_away[$i] = 0;
			$losses_home[$i] = 0;
			$losses_away[$i] = 0;
			$i++;
	}
	 // check userid
	 while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = true; // user already exists !!
			}
	}
	 // check rows in the division
	while($row = mysql_fetch_array($fields)) {
				if($_SESSION['dcode']== $row['dcode']){
					$dname = $row['dname'] ;
				}
			}
	
	// check rows in the schedule
	while($row = mysql_fetch_array($schedule)) {
				if($_SESSION['dcode']== $row['dcode']){
					$gametime = $row['gametime'];
					$gym = $row['gym'];
					$awayteam = $row['awayteam'];
					$hometeam = $row['hometeam'];
					$awayscore = $row['awayscore'];
					$homescore = $row['homescore'];
					
					if($awayscore > $homescore){
						// HOME ROAD calculation
						$wins_home[$hometeam] = $wins_home[$hometeam] +0;
						$wins_away[$awayteam] = $wins_away[$awayteam] +1;
						$losses_home[$hometeam] = $losses_home[$hometeam] +1;
						$losses_away[$awayteam] = $losses_away[$awayteam] +0;
					}
					else if($awayscore < $homescore){
						$wins_home[$hometeam] = $wins_home[$hometeam] +1;
						$wins_away[$awayteam] = $wins_away[$awayteam] +0;
						$losses_home[$hometeam] = $losses_home[$hometeam] +0;
						$losses_away[$awayteam] = $losses_away[$awayteam] +1;
					}
					else{
						$wins_home[$hometeam] = $wins_home[$hometeam] +0;
						$wins_away[$awayteam] = $wins_away[$awayteam] +0;
						$losses_home[$hometeam] = $losses_home[$hometeam] +0;
						$losses_away[$awayteam] = $losses_away[$awayteam] +0;
					} 
				}
			}
	// check rows in the team
	$num = 0;
	$tno = 0;
	while($row = mysql_fetch_array($team)) {
					if($_SESSION['dcode']== $row['dcode']){
						$tno = $row['tno'];
						$coach[$num] = $row['coach'];
						// HOME ROAD 
						$homerecord_win[$num] = $wins_home[$tno];
						$homerecord_lose[$num] = $losses_home[$tno];
						$roadrecord_win[$num] = $wins_away[$tno];
						$roadrecord_lose[$num] = $losses_away[$tno];
						
						$wins[$num] = $wins_home[$tno]+$wins_away[$tno];
						$losses[$num] = $losses_home[$tno]+$losses_away[$tno];
						// Total Games calculation
						$games[$num] = $wins[$num] + $losses[$num];
						// Win Percent calculation
						$winpercent[$num] = round(($wins[$num] / $games[$num]) * 100,1);
						$team_num[$num] = $tno;
						 
						$data[$num] = array('team_num' => $team_num[$num], 'coach' => $coach[$num], 'games' => $games[$num], 'wins'=> $wins[$num]
						,'losses' => $losses[$num], 'winpercent' => $winpercent[$num], 'gamesbehind'=> $gamesbehind[$num]
						,'homerecord_win' => $homerecord_win[$num],'homerecord_lose' => $homerecord_lose[$num]
						, 'roadrecord_win' => $roadrecord_win[$num],'roadrecord_lose' => $roadrecord_lose[$num]);
						$num++;
					}
			}
	// array sort by win percent
	array_multisort($winpercent, SORT_DESC, $coach, SORT_ASC, $data);
			
	foreach ($data as $key => $row) {
				$team_num[$key] = $row['team_num'];
				$coach[$key]    = $row['coach'];
				$games[$key] = $row['games'];
				$wins[$key] = $row['wins'];
				$losses[$key] = $row['losses'];
				$winpercent[$key] = $row['winpercent'];
				$gamesbehind[$key] = $row['gamesbehind'];
				$homerecord_win[$key] = $row['homerecord_win'];
				$homerecord_lose[$key] = $row['homerecord_lose'];
				$roadrecord_win[$key] = $row['roadrecord_win'];
				$roadrecord_lose[$key] = $row['roadrecord_lose'];
				// Games Behind calculation 
				if($key==0){
					$gamesbehind[$key] = 0.0;
				}
				else{
					$gamesbehind[$key] = (($wins[0]-$losses[0])-($wins[$key]-$losses[$key]))*0.5;
				}
				
				$show_list[$key]= "<tr height=\"25\"><td><div align=\"center\">$team_num[$key]</div></td>
				<td><div align=\"center\"><a href=\"game_details.php?dcode=$dcode&team_num=$team_num[$key]&coach=$coach[$key]\">$coach[$key]</a></div></td>
						<td><div align=\"center\">$games[$key]</div></td>
						<td><div align=\"center\">$wins[$key]</div></td>
						<td><div align=\"center\">$losses[$key]</div></td>
						<td><div align=\"center\">$winpercent[$key]</div></td>
						<td><div align=\"center\">$gamesbehind[$key]</div></td>
						<td><div align=\"center\">$homerecord_win[$key]</div></td>
						<td><div align=\"center\">$homerecord_lose[$key]</div></td>
						<td><div align=\"center\">$roadrecord_win[$key]</div></td>
						<td><div align=\"center\">$roadrecord_lose[$key]</div></td>
						</tr>";
			}
	
?>
<html>
<head>
<title>Standing</title>
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
<td>Division Name:<strong>
<?php
		echo $dname;
?></strong>
</td>
</tr></table>
	
<table align=center width="500" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td><div align="center">TEAM NUM</div></td>
<td><div align="center">TEAM(COACH)</div></td>
<td><div align="center">GAMES</div></td>
<td width="40"><div align="center">W</div></td>
<td width="40"><div align="center">L</div></td>
<td width="40"><div align="center">PCT</div></td>
<td width="40"><div align="center">GB</div></td>
<td><div align="center">H_W</div></td>
<td><div align="center">H_L</div></td>
<td><div align="center">R_W</div></td>
<td><div align="center">R_L</div></td>
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
        <input name="button" type="button" style="background-color:white; color:black;" onClick="location.href='division_listing.php'"  value="Back">
		</input>
        </div></td>
</tr>
<tr>
  <td><span class="style2"> Glossary</span></td>
</tr>
<tr>
<td>
  <span class="style6"><strong>GAMES</strong>: Game Number &emsp;
  <strong>W</strong>: Wins &emsp;
  <strong>L</strong>: Losses &emsp;
  <strong>PCT</strong>: Win Percentage &emsp;
  <strong>GB</strong>: Games Behind 
  </span></td>
</tr>
<tr><td><span class="style6"><strong>H_W</strong>: Win Home Record&emsp;
      <strong>H_L</strong>: Lose Home Record &emsp;
	  <strong>R_W</strong>: Win Road Record &emsp;
	  <strong>R_L</strong>: Lose Road Record  
</span></td>
</tr>
</table>

</body>
</html>