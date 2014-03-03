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
	$usercheck = "false";
	$userid = "";
	$usertype = 0;	
	$tno = 0;
	$coach ="";
		
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
	 	//$coach = $_POST['coach'];
		$tno = $_POST['tno'];
		$coachid = $_POST['coachid'];
		$coachpasswd = $_POST['coachpasswd'];
		$dcode = $_SESSION['dcode'];
		
		
		$error_arr = array();
		
						
		if($dcode ==""){
                $error_arr['tno'] = 'Please put correct division name';
        }
		else if($tno=="None"){
                $error_arr['tno'] = 'Please put correct team number';
        }
		
		
		else if($coachid ==""){
                $error_arr['tno'] = 'Please put correct coach name';
        }
		else if($coachpasswd ==""){
                $error_arr['tno'] = 'Please put correct coach name';
        }
		
		else{
				//$valid_arr['coach'] = $coach;
				$valid_arr['tno'] = $tno;
		}
		
		if(count($error_arr) == 0){
			$_SESSION['dcode'] = $dcode;
			$_SESSION['coachid'] = $coachid;
			$_SESSION['tno'] = $valid_arr['tno'];
			
			$_SESSION['coachpasswd'] = $coachpasswd;
			$_SESSION['usertype'] = 3;

			mysql_query("INSERT INTO users (userid, password, usertype, divisionCode, teamNumber)
				VALUES ('{$_SESSION['coachid']}','{$_SESSION['coachpasswd']}','{$_SESSION['usertype']}','{$_SESSION['dcode']}', '{$_SESSION['tno']}')");
				header('location: add_coach.php');		
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
      window.location.href = "add_coach1.php?dcode="+selectedvalue[0]+"&userid="+selectedvalue[1];
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
<table align=center width="600" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td width="120"><div align="center">DIVISION</div></td>
<td width="150"><div align="center">COACH ID</div></td>
<td width="150"><div align="center">COACH PASSWD</div></td>
<td><div align="center">TEAM NUMBER</div></td>
</tr>
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
<td><div align="center"><input type="text" name="coachid" id="coachid" value="<?php echo $coachid;?>" maxlength="15" size="15"/></div></td>
<td><div align="center"><input type="text" name="coachpasswd" id="coachpasswd" value="<?php echo $coachpasswd;?>" maxlength="25" size="10"/></div></td>
<td>
	<select name="tno" id ="tno" onChange="divisionselected(this)">;
	<option value="None">None</option>;
<?php
 		$team_query = mysql_query("SELECT * FROM team WHERE (dcode = '{$_SESSION['dcode']}')&&(coach='None')");
        while($row = mysql_fetch_array($team_query)) {
		//$dcode = $row['dcode'];
				if($usertype == 1){
                	echo '<option value="'.$row['tno'].'">'.$row['tno'].'</option>'; 
				 }
				 else {
				 	if($divisionCode == $row['dcode']){
					 	echo '<option value="'.$row['tno'].'">'.$row['tno'].'</option>'; 
					 }
				 }
        }
    echo '</select>';		
?>
</td>
</tr>
</table>

<table align=center width="600"><tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['tno'];?>
</td></tr><tr><td align="right"><input type="button"  onClick="location.href='add_coach.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Submit" style="background-color:white; color:black;"></input></td></tr>
</form>
</table>
<tr><td>&nbsp;</td></tr>
<table align=center width="600">
<tr><td align="left">
<align="left" style="color:red; font-size: 12px;"/>
<ul>
  ** Important : If you want to add a new team and a new coach,
  <li>First of all, you have to create a team with "None" coach and assign a new team number for the team.</li>
  <li>Then you need to go back to Admin Homepage to create a new coach by clicking Add coach link (right below create team link) and link the coach with the team number which will be shown in dropdown list. </li>
  <li>Last you should go back to update the team with the new coach (in team update link not this page Add team).  </li>
</ul>
</td></tr>
</table>
</body>
</html>