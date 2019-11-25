<?php
	require('connect.php');

	if($_POST && isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password1']))
	{
		$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM users";
		$statement = $db->prepare($query);
		$statement->execute();

		//check if username is unique
		$isUnique = false;
		$count = 0;

		//check all username from db if unique
		while($row = $statement->fetch())
		{
			if($row['username'] == $username)
			{
				$count = $count + 1;
			}
		}

		if($count == 0)
		{
			$isUnique = true;
		}

		if((strlen($fullname) > 0) && (strlen($email) > 0) && (strlen($username) > 0) && (strlen($password) > 0) && (strlen($password1) > 0))
		{
			if ($isUnique == true) 
			{
				if($password == $password1)
				{
					//hash password
					$password = password_hash($password, PASSWORD_DEFAULT);

					$query = "INSERT INTO users (fname, email, username, password, userType) VALUES (:fname, :email, :username, :password, :type)";
					$statement = $db->prepare($query);

					$statement->bindValue(':fname', $fullname);
	      			$statement->bindValue(':email', $email);
	      			$statement->bindValue(':username', $username);
	      			$statement->bindValue(':password', $password);
	      			$statement->bindValue(':type', $type);

	      			$statement->execute();

	      			header("refresh:1; url=login.php");
					echo '<script language ="javascript">';
					echo 'alert("Registration is successful. Redirecting to the login page.")';
					echo '</script>';
				}
				else
				{
					echo '<script language="javascript">';
					echo 'alert("Password must match")';
					echo '</script>';				}
			}
			else
			{
				echo '<script language ="javascript">';
				echo 'alert("Username is already taken.")';
				echo '</script>';
			}
		}
		else
		{
			echo '<script language="javascript">';
			echo 'alert("Inputs are required")';
			echo '</script>';
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Register Account</h1>
	<div id="content">
		<form method="post">
			<div>
				<label for="fullname">Full Name:</label>
				<input id="fullname" type="text" name="fullname">
			</div>
			<div>
				<label for="email">Email:</label>
				<input id="email" type="text" name="email">
			</div>
			<div>
				<label for="username">Username:</label>
				<input id="username" type="text" name="username">
			</div>
			<div>
				<label for="password">Password:</label>
				<input id="password" type="password" name="password">
				<label for="password1">Re-enter Password:</label>
				<input id="password1" type="password" name="password1">
			</div>
			<div>
				<input id="type" type="hidden" value="Registered-User" name="type">
			</div>
			<div>
				<input id="submit" type="submit">
			</div>
		</form>
	</div><br>
	<div>
		<p>Already a member? Login <a href="login.php">here</a></p>
		<p><a href="index.php">Go Back</a> to the Home Page</p>
	</div>
</body>
</html>