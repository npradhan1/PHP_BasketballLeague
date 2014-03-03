<?php
	// connect to database
	require 'connect.php';
	
	$user_query = mysql_query("SELECT * FROM users");
	$schedule = mysql_query("SELECT * FROM schedule ORDER BY gametime ASC") or die("Unable to connect to query");
	
	// variables
	$dcode = "";
	$dname = "";
	$temptime ="";
	$temp = "";
	$num = 0;
	$usercheck = false;	
	$monthcheck = false;
	
	// check login user
	session_start();
	
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = true; // user already exists !!
			}
	}
	
	// check rows in the table
	while($row = mysql_fetch_array($schedule)) {
				
				$temptime = date_create($row['gametime']);
				$gamemonth[$num] = date_format($temptime, 'Y-m');
				$gamedate[$num] = date_format($temptime, 'd-M');
				$gametime[$num] = date_format($temptime, 'H:i');
				$dcode[$num] = $row['dcode'];
				$gym[$num] = $row['gym'];
				$hometeams[$num] = $row['hometeam'];
				$awayteams[$num] = $row['awayteam'];
				$homescores[$num] = $row['homescore'];
				$awaysocres[$num] = $row['awayscore'];
				
				// check current month
				switch ($gamemonth[$num]) {
					case "2012-10":
						$monthcheck = false;
						break;
					case "2012-11":
						$monthcheck = false;
						break;
					case "2012-12":
						$monthcheck = false;
						break;
					case "2013-01": // current month
						$monthcheck = false;
						break;
					case "2013-02":
						$monthcheck = true;
						break;
				}
				
				// when the date is 2013-01: default
				if($monthcheck == true){
					if($homescores[$num] == -1){
						$homescores[$num] ="";
						$awaysocres[$num] ="";
					}
					// find division name
					$fields = mysql_query("SELECT * FROM division WHERE dcode='{$dcode[$num]}'") or die("Unable to connect to division");
						while($row = mysql_fetch_array($fields)) {
									$dname[$num] = $row['dname'];
						}
					// find team name
					$team = mysql_query("SELECT * FROM team WHERE dcode='{$dcode[$num]}'") or die("Unable to connect to team");
						while($row = mysql_fetch_array($team)) {
							if($hometeams[$num] == $row['tno']){
									$hometeams[$num] = $row['coach'];
							}
							else if($awayteams[$num] == $row['tno']){
									$awayteams[$num] = $row['coach'];
							}
					}
					
					$data[$num] = array('dcode' => $dcode[$num], 'gamedate' => $gamedate[$num], 'dname' => $dname[$num], 'awayteams'=> $awayteams[$num]
								, 'hometeams'=> $hometeams[$num], 'awaysocres'=> $awaysocres[$num], 'homescores'=> $homescores[$num]
								, 'gametime' => $gametime[$num], 'gym' => $gym[$num]
								
								);
					$num++;
				}
	}
	// if data is exsist ~
	if(count($data)>0){
		foreach ($data as $key => $row) {
					$dcode[$key] = $row['dcode'];
					$gamedate[$key] = $row['gamedate'];
					$dname[$key] = $row['dname'];
					$awayteams[$key] =  $row['awayteams'];
					$hometeams[$key] =  $row['hometeams'];
					$awaysocres[$key] =  $row['awaysocres'];
					$homescores[$key] =  $row['homescores'];
					$gametime[$key] = $row['gametime'];
					$gym[$key] = $row['gym'];
					$team =""; // TEAM column
					
					// compare hometeam score with awayteam score
					if ($awaysocres[$key] > $homescores[$key]){
						$team = "<strong>$awayteams[$key] $awaysocres[$key]</strong> : $homescores[$key] $hometeams[$key]";
					}
					else if($awaysocres[$key] < $homescores[$key]){
						$team = "$awayteams[$key] $awaysocres[$key] : <strong>$homescores[$key] $hometeams[$key]</strong>";
					}
					else{
						$team = "$awayteams[$key] $awaysocres[$key] : $homescores[$key] $hometeams[$key]";
					}

					$show_list[$key]= "<tr height=\"28\"><td><div align=\"center\">$gamedate[$key]</div></td>
							<td><div align=\"center\"><a href=\"standing.php?dcode=$dcode[$key]\">$dname[$key]</a></div></td>
							<td><div align=\"center\">$team</div></td>
							<td><div align=\"center\">$gametime[$key]</div></td>
							<td><div align=\"center\">$gym[$key]</div></td>
							</tr>";
		}
	}
?>

<html>
<head>
<title>Main</title>
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
<tr><td align="center"><span class="style1">2012-13 East Marietta Basketball </span></td>
</tr>	
<tr><td align="center"><align="left" style="color:black; font-size: 12px;" >
    <?php echo ($usercheck == true ? "You are logged in as '{$_SESSION['userID']}' &nbsp; 
	<a href=\"admin.php\">Admin<a> &nbsp; <a href=\"logout.php\">Logout<a>" : "
	<p>Login to register for the basketball 2012-2013 season. 
	<a href=\"login.php\">Login</a> &emsp; <a href=\"sitemap.php\">Sitemap</a> </p>"); ?> </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center" style="color:black; font-size: 15px;" >
	<a href="main.php">Game Schedule</a> &emsp;|&emsp; 
	<a href="division_listing.php">League Standings</a></td>
</tr>
<tr><td style="border-bottom: solid 1px gray;">&nbsp;</td>
</tr>
<tr><td><a href="schedule_list.php?gamemonth=2012-10">2012 October</a>  &emsp;|&emsp;
<a href="schedule_list.php?gamemonth=2012-11">November</a>  &emsp;|&emsp;
<a href="schedule_list.php?gamemonth=2012-12">December</a>  &emsp;|&emsp;
<a href="schedule_list.php?gamemonth=2013-01">2013 January</a>  &emsp;|&emsp;
<a href="schedule_list.php?gamemonth=2013-02">February</a> </td>
</tr>
<tr><td>&nbsp;</td></tr>
</table>
<table align=center border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td width="50"><div align="center" >DATE</div></td>
<td width="150"><div align="center">DIVISION</div></td>
<td width="220"><div align="center">TEAM</div></td>
<td width="50"><div align="center">TIME</div></td>
<td width="150"><div align="center">GYM</div></td>
</tr>

<?php
	$num = 0;
	while ($num < count($data)){
			echo $show_list[$num];
			$num++;
		}
?>
</table>
           
</body>
</html>