<?php
	// connect to database
	require 'connect.php';
	$user_query = mysql_query("SELECT * FROM users");
	$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	$schedule = mysql_query("SELECT * FROM schedule ORDER BY gametime ASC") or die("Unable to connect to query");
	
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
	$coach_add_check = "false";
	$coach_update_check = "false";
	$coach_delete_check = "false";
	$division_update_check = "false";
	
	session_start();
	
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = "true"; // user already exists !!
				$userid = $row['userid'];
				$usertype = $row['usertype'];
				$divisionCode = $row['divisionCode'];
			}
	}
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$division = $_POST['division'];
		$commissioner = $_POST['commissioner'];
		$team = $_POST['team'];
		$schedule = $_POST['schedule'];
		$coach = $_POST['coach'];
	 	$game_division = $_POST['game_division'];
		
		$error_arr = array();

		if($usertype == 1){
			if (($division == "None")&($commissioner == "None")&($team == "None")&($schedule == "None")&($coach == "None")&($game_division == "None")) {
					$error_arr['error'] = 'Please select the list';
			}
			else{
					$valid_arr['division'] = $division;
					$valid_arr['commissioner'] = $commissioner;
					$valid_arr['team'] = $team;
					$valid_arr['schedule'] = $schedule;
					$valid_arr['game_coach'] = $coach;
					$valid_arr['game_division'] = $game_division;
			}
		}
		else if($usertype == 2){
			if (($team == "None")&($schedule == "None")&($coach == "None")&($game_division == "None")) {
					$error_arr['error'] = 'Please select the list';
			}
			else{
					$valid_arr['team'] = $team;
					$valid_arr['schedule'] = $schedule;
					$valid_arr['game_coach'] = $coach;
					$valid_arr['game_division'] = $game_division;
			}
		}
		else if($usertype == 3){
			if ($game_division == "None") {
					$error_arr['error'] = 'Please select the list';
			}
			else{
					$valid_arr['game_division'] = $game_division;
			}
		}
		
		if(count($error_arr) == 0){
			$_SESSION['userID'] = $userid;
			$_SESSION['usertype'] = $usertype;
			$_SESSION['divisionCode'] = $divisionCode;

			$_SESSION['division_list'] = $valid_arr['division'];
			$_SESSION['commissioner'] = $valid_arr['commissioner'];
			$_SESSION['team'] = $valid_arr['team'];
			$_SESSION['schedule'] = $valid_arr['schedule'];
			$_SESSION['game_coach'] = $valid_arr['game_coach'];
			$_SESSION['game_division'] = $valid_arr['game_division'];
			
			header('location: update.php');		
		}
	}
?>
<html>
<head>
<title>Admin Page</title>
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
		echo ($usercheck == "true" ? "You are logged in as '{$_SESSION['userID']}' &nbsp; <a href=\"logout.php\">Logout<a>" : "<p>Login to register for the basketball 2012-2013 season. <a href=\"login.php\">Login</a> </p>"); 
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
<form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

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
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Division List</legend>
<B>Select a division:</B> &nbsp;&nbsp;
	<select name="division" id ="division"<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> style="width: 200;">;
	<option value="None">None</option>;
 <?php
        while($row = mysql_fetch_array($fields)) {
		//$dcode = $row['dcode'];
				//$dname = $row['dname'] ;
			//	$show_dname .= "<a href=\"standing.php?dcode=$dcode\" <strong>$dname</strong></a><br><br>";
                echo '<option value="'.$row['dcode'].'">'.$row['dcode'].", ".$row['dname'].'</option>'; 
			//	echo '<option ' .($dcode==$row['dcode'] ? 'selected' : ''). ' value="'.$row['dcode'].'">'.$row['dname'].'</option>'; 
				 
        }
    echo '</select>';		
?> &emsp;
	<input align="right" type="button" name = "division_add" id= "division_add" onClick="location.href='add.php'" 
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Add" style="background-color:white; color:black;"/>	
	<input align="right" type="submit" name= "division_update" id= "division_update"
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Update" style="background-color:white; color:black;"/>	
	<input align="right" type="submit" name= "division_delete" id= "division_delete" 
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Delete" style="background-color:white; color:black;"/>	
</fieldset>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Commissioner List</legend>
<B>Select a commissioner:</B> &nbsp;&nbsp;
	<select name="commissioner" id ="commissioner" <?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> style="width: 200;">;
	<option value="None">None</option>;
 <?php
 		$commissioner_query = mysql_query("SELECT * FROM users WHERE usertype=2");
        while($row = mysql_fetch_array($commissioner_query)) {
                echo '<option value="'.$row['userid'].'">'.$row['divisionCode'].", ".$row['userid'].'</option>'; 
        }
    echo '</select>';		
?> &emsp;
	<input type="button" name = "commissioner_add" id= "commissioner_add" onClick="location.href='add.php'" 
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Add" style="background-color:white; color:black;"/>	
	<input type="submit" name= "commissioner_update" id= "commissioner_update"
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Update" style="background-color:white; color:black;"/>	
	<input type="submit" name= "commissioner_delete" id= "commissioner_delete" 
	<?php echo ($usertype == 1 ? '' : 'disabled="disabled"') ?> 
	value="Delete" style="background-color:white; color:black;"/>	
</fieldset>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Team List</legend>
<B>Select a team:</B> &nbsp;&nbsp;
	<select name="team" id ="team" <?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?> style="width: 200;">;
	<option value="None">None</option>;
 <?php
 		$team_query = mysql_query("SELECT * FROM team");
        while($row = mysql_fetch_array($team_query)) {
		//$dcode = $row['dcode'];
				//$dname = $row['dname'] ;
				
			//	$show_dname .= "<a href=\"standing.php?dcode=$dcode\" <strong>$dname</strong></a><br><br>";
                echo '<option value="'.$row['coach'].'">'.$row['dcode'].", ".$row['coach'].'</option>'; 
			//	echo '<option ' .($dcode==$row['dcode'] ? 'selected' : ''). ' value="'.$row['dcode'].'">'.$row['dname'].'</option>'; 
				 
        }
    echo '</select>';		
?> &emsp;
	<input type="button" name = "team_add" id= "team_add" onClick="location.href='add.php'" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Add" style="background-color:white; color:black;"/>	
	<input type="submit" name= "team_update" id= "team_update" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Update" style="background-color:white; color:black;"/>	
	<input type="submit" name= "team_delete" id= "team_delete" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Delete" style="background-color:white; color:black;"/>	
</fieldset>
</td>
</tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Schedule List</legend>
<B>Select a schedule:</B> &nbsp;&nbsp;
	<select name="schedule" id ="schedule" <?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?> style="width: 200;">;
	<option value="None">None</option>;
 <?php
 		$schedule_query = mysql_query("SELECT * FROM division");
        while($row = mysql_fetch_array($schedule_query)) {
		//$dcode = $row['dcode'];
				//$dname = $row['dname'] ;
				
			//	$show_dname .= "<a href=\"standing.php?dcode=$dcode\" <strong>$dname</strong></a><br><br>";
                echo '<option value="'.$row['dcode'].'">'.$row['dcode'].", ".$row['dname'].'</option>'; 
			//	echo '<option ' .($dcode==$row['dcode'] ? 'selected' : ''). ' value="'.$row['dcode'].'">'.$row['dname'].'</option>'; 
				 
        }
    echo '</select>';		
?> &emsp;
	<input type="button" name = "schedule_add" id= "schedule_add" onClick="location.href='add.php'" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Add" style="background-color:white; color:black;"/>	
	<input type="submit" name= "schedule_update" id= "schedule_update" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Update" style="background-color:white; color:black;"/>	
	<input type="submit" name= "schedule_delete" id= "schedule_delete" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Delete" style="background-color:white; color:black;"/>	
</fieldset>
</td>
</tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Coach List</legend>
	<input type="button" name = "coach_add" id= "coach_add" onClick="location.href='add.php'" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Add" style="background-color:white; color:black;"/>	
	<input type="button" name= "coach_update" id= "coach_update" onClick="location.href='update_coach.php?userid=<?php echo $userid ?>'" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Update" style="background-color:white; color:black;"/>	
	<input type="submit" name= "coach_delete" id= "coach_delete" 
	<?php echo (($usertype == 1)|($usertype == 2) ? '' : 'disabled="disabled"') ?>
	value="Delete" style="background-color:white; color:black;"/>	
</fieldset>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td>
<fieldset style="width: 530;">
<legend>Game Score</legend>
<B>Select a division:</B> &nbsp;&nbsp;
	<select name="game_division" id ="game_division" onChange="divisionselected(this)" <?php echo (($usertype == 1)|($usertype == 2)|($usertype == 3) ? '' : 'disabled="disabled"') ?> style="width: 200;">;
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
?> &emsp;
	
	<input type="button" value="Update" onClick="location.href='update_score.php?userid=<?php echo $userid ?>&dcode=<?php echo $userid ?>'"  style="background-color:white; color:black
	<?php echo (($usertype == 1)|($usertype == 2)|($usertype == 3) ? '' : 'disabled="disabled"') ?>;"></input>
	
</fieldset>
</td>
</tr>
<tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['error'];?>
</td></tr>
</form>
<tr><td>&nbsp;</td></tr>
</table>
</body>
</html>