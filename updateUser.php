<?php
	require('connect.php');

	$query = "SELECT * FROM users WHERE userID = '$_GET[userID]'";
	$statement = $db->prepare($query);
	$statement->execute();

	if(isset($_POST['update']))
	{
		$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);

		if((strlen($fullname) > 0) && (strlen($username) > 0) && (strlen($email) > 0))
		{
			$query = "UPDATE users SET fname ='$_POST[fullname]', email = '$_POST[email]', username = '$_POST[username]', userType = '$_POST[accountType]'
			WHERE userID = '$_GET[userID]' ";  

			$statement = $db->prepare($query);
			$statement->bindValue(':fullname', $fullname);
			$statement->bindValue(':username', $username);
			$statement->bindValue(':email', $email);
			$statement->bindValue(':id', $id , PDO::PARAM_INT);

			$statement->execute();

			header("Location:allUsers.php");
		}
		else
		{
			header("");
		}
	}

	if(isset($_POST['delete']))
	{
		$row = $statement->fetch();

		$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);

		$query = "DELETE FROM users WHERE userID = '$_GET[userID]'";  
		$statement = $db->prepare($query);
		$statement->bindValue(':fullname', $fullname);
		$statement->bindValue(':username', $username);
		$statement->bindValue(':email', $email);
		$statement->bindValue(':id', $id , PDO::PARAM_INT);

		$statement->execute();

		header("Location:allUsers.php");
	}

	function call()
	{
		header("Location:allUsers.php");
		exit;
	}

	if(!isset($_GET['userID']) || ($_GET['userID'] < 1) || (!is_numeric($_GET['userID'])))
	{
		call();
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<?php while($row = $statement->fetch()): ?>
	<title>Update a User</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Update Users</h1>
	<h2>UserID: <?= $row['userID'] ?></h2>
	<div id="content">
		<form method="post">
			<div>
				<label for="fullname">Full Name:</label>
				<input value= '<?= $row['fname']?>' id="fullname" type="text" name="fullname">
			</div>
			<div>
				<label for="email">Email:</label>
				<input value= '<?= $row['email']?>' id="email" type="text" name="email">
			</div>
			<div>
				<label for="username">Username:</label>
				<input value= '<?= $row['username']?>' id="username" type="text" name="username">
			</div>
			<div>
				<select name="accountType">
					<option value="Registered-User">Registered-User</option>
					<option value="Admin">Admin</option>
				</select>
			</div>
			<div>
				<input id='update' type='submit' name='update' value='update' onclick = "return confirm('Update this user??')">
				<input id='submit' name='delete' type='submit' value='delete' onclick = "return confirm('Delete this user?')">
			</div>
		</form>
	</div>
	<div>		
		<p><a href="allUsers.php">Go Back</a></p>
	</div>
</body>
	<?php endwhile ?>
</html>