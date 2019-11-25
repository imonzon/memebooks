<?php
	require("connect.php");

	$query = "SELECT * FROM users WHERE userID = '$_GET[userID]'";
	$statement = $db->prepare($query);
	$statement->execute();

	$query1 = "SELECT * FROM posts WHERE userId = '$_GET[userID]' ORDER BY Pdate DESC";
	$statement1 = $db->prepare($query1);
	$statement1->execute();

	session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php while($row = $statement->fetch()): ?>
	<title><?= $row['fname'] ?>'s Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="profile">
		<?php if(isset($_SESSION['userID'])): ?>
			<p><a href="logout.php">Logout</a></p>
		<?php endif ?>
		<h1><?= $row['fname'] ?>'s Profile</h1>
		<h3>Email: <?= $row['email'] ?></h3>
	</div>
	<div id="navbar">
    	<nav>
	      	<ul>
	      		<li><a href="index.php">Home</a></li>
	        	<?php if(isset($_SESSION['userID'])): ?>
	        		<li><a href="createP.php">Post MEME</a></li>
				<?php endif ?>
	      	</ul>
    	</nav>
  	</div><br>
	<div id="content">
		<form id="search" method="post" action="search.php">
    		<input type="text" name="searchContent" placeholder="Search by title/profile">
    		<input id='search' type='submit' name="search" value="Search">
    	</form>
		<h2>My Delicious MEMEs</h2>
		<?php while($row1 = $statement1->fetch()): ?>
		<div>
			<div>
				<h5><?= $row1['PTitle'] ?></h5>
				<p><?= date("F d, Y, g:i a", strtotime($row1['PDate'])); ?></p>
			</div>
			<p><?= substr($row1 ['PContent'],0,200) ?>
			<?php if(isset($_SESSION['userID'])): ?>
					<?php if($row['userType'] == "Admin"): ?>
						- <a href="updateP.php?id=<?= $row['PostId']?>">Edit</a></p>
					<?php elseif($row1['userId'] == $_SESSION['userID'] && $row['userType'] != "Admin"): ?>
						- <a href="updateP.php?id=<?= $row1['PostId']?>">Edit</a></p>
					<?php else: ?>
						</p>
					<?php endif ?>
			<?php endif ?>
			<?php $new_image_path = dirname(__FILE__) . '\images\\' . $row1['imageP']; ?>
			<br><img src="images/<?= $row1['imageP'] . '_medium' . '.' . pathinfo($new_image_path, PATHINFO_EXTENSION) ?>" alt="<?= $row1['imageP'] ?>"> 
		</div><br>
		<?php endwhile ?>
	</div>
</body>
	<?php endwhile?>
</html>