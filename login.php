<?php
	require 'connect.php';
	//require 'core.php';
    
	if($_SERVER['REQUEST_METHOD']=='POST'){
        $userID = $_POST['userID'];
        $passwd = $_POST['passwd'];

        $error_arr = array();

		if($userID == ''){
				$error_arr['userID'] = 'userID Required';
			}
		else if($passwd == ''){
				$error_arr['passwd'] = 'passwd Required';
			}
		else{
				$valid_arr['userID'] = $userID;
				$valid_arr['passwd'] = $passwd;
			}

		if(count($error_arr) == 0){
		 	session_start();
		   	$_SESSION['userID'] = $valid_arr['userID'];
			$_SESSION['passwd'] = $valid_arr['passwd'];

			$user_query = mysql_query("SELECT * FROM users where userid = '{$_SESSION['userID']}' AND `password` = '{$_SESSION['passwd']}'") or die("Unable to connect to query");
			//$usertype_query = mysql_query("SELECT usertype FROM users where userid = '{$_SESSION['userID']}' AND `password` = '{$_SESSION['passwd']}'") or die("Unable to connect to query");

			$query_num_rows = mysql_num_rows($user_query);

			if($query_num_rows == 0){
					$error_arr['passwd'] = 'Invalid Username/Password';
			}
			else if($query_num_rows == 1){
				while($row = mysql_fetch_array($user_query)) {
					if ($row['userid '] == $_SESSION['userID']){
						$_SESSION['userType']=$row['usertype '];
						
					}
				}
				header('Location: main.php');
			}
			else{
				echo 'Error Occured';
			}
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

<tr>
    <td align="center"><span class="style1">2012-13 East Marietta Basketball </span></td>
</tr>	
<tr>
    <td align="center"><align="left" style="color:gray; font-size: 9px;" >
    <p>Login to register for the basketball 2012-2013 season. <a href="login.php">Login</a> </p>
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
<form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
<tr height="30"><td align="right">
	<align="center" style="color:black; font-size: 12px;"> 
	UserID: &nbsp;
	<input type='text' name="userID" size="20" value="<?php echo $valid_arr['userID'];?>"/>&nbsp;
	<br /><?php echo $error_arr['userID'];?>
	</td>
</tr>
<tr height="30" ><td align="right">
	PassWD:
	
	<input type='password' name="passwd" size="20" value="<?php echo $valid_arr['passwd'];?>"/>&nbsp;
	<br /><?php echo $error_arr['passwd'];?>
	</td>
</tr>
<tr><td align="right"><input type="submit" value="Submit" style="background-color:white; color:black;"/></td>
</tr>
</form>
</table>
</body>
</html>