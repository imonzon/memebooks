<?php
	require('connect.php');
	session_start();

	if($_POST && isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']))
	{
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM users WHERE username = '$username'";
		$statement = $db->prepare($query);
		$statement->execute();

		if($statement->RowCount() >= 1)
		{
			$row = $statement->fetch();

			if(password_verify($_POST['password'], $row['password']))
			{
				$_SESSION['userID'] = $row['userID'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['fname'] = $row['fname'];

				header("refresh:.5; url=index.php");
			}
			else
			{
				echo '<script language ="javascript">';
				echo 'alert("Password is incorrect")';
				echo '</script>';
			}
		}
		else
		{
			session_destroy();
			echo '<script language ="javascript">';
			echo 'alert("Username is incorrect!")';
			echo '</script>';
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Log In</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Log-In</h1>
	<div id="content">
		<form method="post">
			<div>
				<label for="username">Username:</label>
				<input id="username" type="text" name="username">
			</div>
			<div>
				<label for="password">Password:</label>
				<input id="password" type="password" name="password">
			</div>
			<div>
				<input type="submit" name="submit">
			</div>
		</form>
	</div><br><br>
	<div>
		<p>Not a member yet? <a href="register.php">Sign up</a> Now</p>
		<p><a href="index.php">Go Back</a> to the Home Page</p>
	</div>
</body>
</html>