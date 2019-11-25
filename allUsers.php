<?php
	require("connect.php");
	$query = "SELECT * FROM users ORDER BY userID ASC";
	$statement = $db->prepare($query);
	$statement->execute();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Registered Users</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="header">
		<h1>All Registered Users</h1>
	</div>
	<div>
		<p><a href="register.php">Add New User</a></p>
		<table>
			<thead>
				<tr>
					<th></th>
					<th>User ID</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Username</th>
					<th>Account Type</th>
				</tr>
			</thead>
			<tbody>
				<?php while($row=$statement->fetch()): ?>
					<tr>
						<td><a href="updateUser.php?userID=<?= $row['userID']?>">Update</a></td>
                		<td><?= $row['userID'] ?></td>
		                <td><?= $row['fname'] ?></td>
		                <td><?= $row['email'] ?></td>
		                <td><?= $row['username'] ?></td>
		                <td><?= $row['userType'] ?></td>
					</tr>
				<?php endwhile ?>
			</tbody>
		</table>
	</div>
	<footer>
		<p>Go Back to the <a href="index.php">Home Page</a>.</p>
	</footer>
</body>
</html>