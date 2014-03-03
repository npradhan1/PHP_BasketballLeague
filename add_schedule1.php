<?php
	// connect to database
	require 'connect.php';

	if(isset($_GET['userid'])) {
			session_start(); 
			$_SESSION['dcode'] = $_GET['dcode'];
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
	
	
	while($row = mysql_fetch_array($fields)) {
			if ($row['dcode'] == $_SESSION['dcode']){
				 $_SESSION['dname'] = $row['dname'];
			 }
	}
	
	if($_SERVER['REQUEST_METHOD']=='POST'){
		
		//$division = $_POST['division'];
		//$list = split(",", $division);
		$dcode =$_SESSION['dcode'];
		
		$awayteam = $_POST['awayteam'];	
		$hometeam = $_POST['hometeam'];	
		$gym = $_POST['gym'];	
		$gid = $_POST['gid'];
		$tempgamedate= $_POST['gamedate'];
		$tempgametime=$_POST['gametime'];
		
		$datetime = $_POST['gamedate'] . ' ' . $_POST['gametime'] . ':00';
		$datetime = mysql_real_escape_string($datetime);
				
		$error_arr = array();
		
		$gid_query = mysql_query("SELECT * FROM schedule WHERE dcode='$dcode'") or die("Unable to connect to query");
		$gid_check = "true";
		
		while($row = mysql_fetch_array($gid_query)) {
			if ($row['gid'] == $gid ){
				$gid_check = "false";
			}
		}
		if($dcode ==""){
                $error_arr['error'] = 'Add Error Please Check the division';
        }
		else if($gid==""){
                $error_arr['error'] = 'Add Error Please Check the gid';
        }
		
		else if(!preg_match('/^[0-9]+$/',$gid)){
                $error_arr['error'] = 'Add Error Please Check the gid';
        }
		else if($gid_check =="false"){
                $error_arr['error'] = 'gid exists !! please put the another gid';
        }
		else if($datetime ==""){
                $error_arr['error'] = 'Add Error Please Check the datetime';
        }
		else if($tempgamedate ==""){
                $error_arr['error'] = 'Add Error Please Check the DATE';
        }
		else if($tempgametime ==""){
                $error_arr['error'] = 'Add Error Please Check the TIME';
        }
		
		else if($awayteam =="None"){
                $error_arr['error'] = 'Add Error Please Check the awayteam';
        }
		else if($hometeam =="None"){
                $error_arr['error'] = 'Add Error Please Check the hometeam';
        }
		
		else if($gym ==""){
                $error_arr['error'] = 'Add Error Please Check the gym';
        }
		
		else if($awayteam ==$hometeam){
                $error_arr['error'] = 'The team can not be same';
        }
		else{
				$valid_arr['dcode'] = $dcode;
				$valid_arr['awayteam'] = $awayteam;
				$valid_arr['hometeam'] = $hometeam;
				$valid_arr['gym'] = $gym;
				$valid_arr['gid'] = $gid;
				$valid_arr['datetime'] = $datetime;
		}
		
		if(count($error_arr) == 0){
		
			$_SESSION['dcode'] = $valid_arr['dcode'];
			$_SESSION['awayteam'] = $valid_arr['awayteam'];
			$_SESSION['hometeam'] = $valid_arr['hometeam'];
			$_SESSION['gym'] = $valid_arr['gym'];
			$_SESSION['gid'] = $valid_arr['gid'];
			$_SESSION['datetime'] = $valid_arr['datetime'];
			 
			 mysql_query("INSERT INTO schedule (dcode, gid, gametime, gym, awayteam, hometeam)
					VALUES ('{$_SESSION['dcode']}', '{$_SESSION['gid']}', '{$_SESSION['datetime']}', '{$_SESSION['gym']}'
					, '{$_SESSION['awayteam']}', '{$_SESSION['hometeam']}')");
					
			header('location: add_schedule.php');		
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
<script>
   function valueselect(sel) {
      var value = sel.options[sel.selectedIndex].value;
	  var selectedvalue = value.split(",");
      window.location.href = "add_schedule1.php?dcode="+selectedvalue[0]+"&userid="+selectedvalue[1];
   }
</script>
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
<table align=center width="900" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td><div align="center">DIVISION</div></td>
<td><div align="center">GAME ID</div></td>
<td><div align="center">DATE</div></td>
<td><div align="center">TIME</div></td>
<td><div align="center">Away Team</div></td>
<td><div align="center">Home Team</div></td>
<td><div align="center">GYM</div></td>
</tr>
<tr height="25">
<td>
	<select name="division" id ="division" onchange="javascript:valueselect(this)" >;
	<option value="<?php $_SESSION['dcode'].','.$_SESSION['userID'];?>"><?php if($_SESSION['dcode']==""){ echo "None";}else{echo $_SESSION['dname'];}?></option>;
<?php
 		$division_query = mysql_query("SELECT * FROM division");
        while($row = mysql_fetch_array($division_query)) {
		$selected = $row['dcode'].','.$_SESSION['userID'];
		//$dcode = $row['dcode'];
				if($usertype == 1){
                	echo '<option value="'.$selected.'">'.$row['dname'].'</option>'; 
				 }
				 else {
				 	if($divisionCode == $row['dcode']){
					 	echo '<option value="'.$selected.'">'.$row['dname'].'</option>'; 
					 }
				 }
        }
    echo '</select>';		
?>
</td>
<td><div align="center">						
<input type="text" name="gid" id="gid" maxlength="3" size="2" value="<?php echo $gid;?>" /></div></td>
<td><div align="center">						
<input type='date' name="gamedate" id="gamedate" value="<?php echo $gamedate; ?>"/></div></td>
<td><div align="center">						
<input type="time" name="gametime" id="gametime" value="<?php echo $gametime;?>" /></div></td>

<td>
	<select name="awayteam" id ="awayteam">
	<option value="None">None</option>;
<?php
 		$team_query = mysql_query("SELECT * FROM team WHERE dcode= '{$_SESSION['dcode']}'");
        while($row = mysql_fetch_array($team_query)) {
		//$dcode = $row['dcode'];
			if(!($row['coach'] =="None")){
				if($usertype == 1){
                	echo '<option value="'.$row['tno'].'">'.$row['coach'].'</option>'; 
				 }
				 else {
				 	if($divisionCode == $row['dcode']){
					 	echo '<option value="'.$row['tno'].'">'.$row['coach'].'</option>'; 
					 }
				 }
			}
        }
    echo '</select>';		
?>
</td>
<td>
	<select name="hometeam" id ="hometeam">;
	<option value="None">None</option>;
<?php
 		$team_query = mysql_query("SELECT * FROM team WHERE dcode= '{$_SESSION['dcode']}'");
        while($row = mysql_fetch_array($team_query)) {
		//$dcode = $row['dcode'];
			if(!($row['coach'] =="None")){
				if($usertype == 1){
                	echo '<option value="'.$row['tno'].'">'.$row['coach'].'</option>'; 
				 }
				 else {
				 	if($divisionCode == $row['dcode']){
					 	echo '<option value="'.$row['tno'].'">'.$row['coach'].'</option>'; 
					 }
				 }
			}
        }
    echo '</select>';		
?>
</td>
<td><input type="text" name="gym" id="gym" value="<?php echo $gym;?>" /></td>
</tr>
</table>

<table align=center width="900"><tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['error'];?>
</td></tr><tr><td align="right"><input type="button" onClick="location.href='add_schedule.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Add" style="background-color:white; color:black;"></input></td></tr>
</form>
</table>
<tr><td>&nbsp;</td></tr>
<table align=center width="900">
<tr><td align="left">
<align="left" style="color:red; font-size: 12px;"/>
<ul>
  ** Important: If you can not find your team, you may need to go back to team update link on Admin panel to update your team name first.
</ul>
</td></tr>
</table>
</body>
</html>