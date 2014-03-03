<?php
	// connect to database
	require 'connect.php';
	session_start(); 
	if(isset($_GET['userid'])) {
			//$_SESSION['dcode'] = $_SESSION['game_division'];
			$_SESSION['userID'] = $_GET['userid'];
	}
	// variables
	
	$usercheck = "false";
	
	$userid = "";
	$usertype = 0;	
	$divisionCode ="";
	
	$user_query = mysql_query("SELECT * FROM users");
	//$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
	//$schedule = mysql_query("SELECT * FROM schedule ORDER BY gametime ASC") or die("Unable to connect to query");
	$team = mysql_query("SELECT * FROM team ORDER BY dcode ASC") or die("Unable to connect to query");
	
	
	while($row = mysql_fetch_array($user_query)) {
			if ($row['userid'] == $_SESSION['userID']){
				$usercheck = "true"; // user already exists !!
				$userid = $row['userid'];
				$usertype = $row['usertype'];
				$divisionCode = $row['divisionCode'];
			}
	}
	
	$num = 0;
	while($row = mysql_fetch_array($team)) {
		if($usertype == 1){
					$tno[$num] = $row['tno'];
					$coach[$num] = $row['coach'];
					$dcode[num] = $row['dcode'];
					
					$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
					while($row = mysql_fetch_array($fields)) {
						if ($dcode[num] == $row['dcode']){
							$dname[num] = $row['dname'];
						}
					}
					
					$data[$num] = array('dcode' => $dcode[num], 'dname' => $dname[num], 'coach' => $coach[$num], 'tno' => $tno[$num]);
					$num++;
		}
		if($usertype == 2){
			if($divisionCode == $row['dcode']){
				
					$tno[$num] = $row['tno'];
					$coach[$num] = $row['coach'];
					$dcode[num] = $row['dcode'];
					
					$fields = mysql_query("SELECT * FROM division") or die("Unable to connect to query");
					while($row = mysql_fetch_array($fields)) {
						if ($dcode[num] == $row['dcode']){
							$dname[num] = $row['dname'];
						}
					}
					
					$data[$num] = array('dcode' => $dcode[num],'dname' => $dname[num], 'coach' => $coach[$num], 'tno' => $tno[$num]);
					$num++;
			}
		}
	}
		// array sort by win percent
//		array_multisort($dname, SORT_ASC, $tno, SORT_ASC, $data);
	
	foreach ($data as $key => $row) {
				$dcode[$key] = $row['dcode'];
				$dname[$key] = $row['dname'];
				$coach[$key] = $row['coach'];
				$tno[$key] = $row['tno'];
				
				$show_list[$key]= "<tr height=\"25\">
				<td><div align=\"center\">
				<input type=\"radio\" name=\"delete\" id=\"delete\" value=$coach[$key]>
				</td>
				<td><div align=\"center\">$dname[$key]</div></td>
						<td><div align=\"center\">$coach[$key]</a></div></td>
						<td><div align=\"center\">$tno[$key]</div></td>
						
						</tr>";
			}
			
	if($_SERVER['REQUEST_METHOD']=='POST'){
	 	//$coach = $_POST['coach'];
		//$temp = $_POST['delete'];
		//$data = explode(" ", $temp);
		//$dcode = $data[0];
		//$tno = $data[1];
		//$coach = $_POST['delete'];
		$checked = (isset($_POST['delete']))?true:false;
		
		$error_arr = array();
		
		if($checked == false){
		
                $error_arr['coach'] = 'Please select a coach';
        }
		else{
				$valid_arr['coach'] = $_POST['delete'];
				//$valid_arr['dcode'] = $dcode;
				//$valid_arr['tno'] = $tno;
		}
		
		if(count($error_arr) == 0){
			//$_SESSION['dcode'] = $valid_arr['dcode'];
			//$_SESSION['tno'] = $valid_arr['tno'];
			$_SESSION['coach'] = $valid_arr['coach'];
			//mysql_query("DELETE FROM team  where (dcode='{$_SESSION['dcode']}')&&(tno='{$_SESSION['tno']}')");
			mysql_query("DELETE FROM team  where coach='{$_SESSION['coach']}'");
					header('location: delete_coach.php');		
		}
	}


?>
<html>
<head>
<title>Delete Page</title>
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
<tr><td>&nbsp;</td></tr>
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
<table align=center width="400"><form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
<tr><td align="right"><input type="button" onClick="location.href='admin.php'" value="Back" style="background-color:white; color:black;"></input>
&nbsp;<input type="submit" value="Delete" style="background-color:white; color:black;"></input>
</td></tr>

</table>

<table align=center width="400" border="1" cellpadding="0" cellspacing="0" bordercolor="#dcdee2" bordercolordark="white" bordercolorlight="#DCDEE2">
<tr height="30" bgcolor="#E6E6E6">
<td width="50"><div align="center">SELECT</div></td>
<td width="120"><div align="center">DIVISION</div></td>
<td width="100"><div align="center">TEAM</div></td>
<td><div align="center">TEAM NUMBER</div></td>
</tr>
<?php
	$num = 0;
	while ($num < count($data)){
			echo $show_list[$num];
			$num++;
		}
?>
</table></form>
<table align=center width="400">
<tr><td>
<align="left" style="color:red; font-size: 12px;"/>&nbsp;<?php echo $error_arr['coach'];?>
</td></tr>
</table>
</body>
</html>