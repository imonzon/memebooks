<?php
	require('connect.php');
	
	$orderby = !empty($_GET["orderby"]) ? $_GET['orderby'] :  "PDate";
	$order = !empty($_GET["order"]) ? $_GET['order'] :  "desc";

	$query = "SELECT * FROM posts ORDER BY " . $orderby . " " . $order;
	$statement = $db->prepare($query);
	$statement->execute();

	$dateOrder = "desc";
	$userOrder = "desc";
	$titleOrder = "desc";
	$imageOrder = "desc";

	if($orderby == "PDate" && $order == "desc")
	{
		$dateOrder = "asc";
	}
	if($orderby == "poster" && $order == "desc")
	{
		$userOrder = "asc";
	}
	if($orderby == "PTitle" && $order == "desc")
	{
		$titleOrder = "asc";
	}
	if($orderby == "imageP" && $order == "desc")
	{
		$imageOrder = "asc";
	}

	session_start();
	$user['userType'] = "Guest";
	$userID = 0;

	if(isset($_SESSION['userID']))
	{
		$fname = $_SESSION['fname'];
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];

		$query1 = "SELECT userID, username, fname, userType FROM users WHERE userID = '$userID'";
		$statement1 = $db->prepare($query1);
		$statement1->execute();

		$user = $statement1->fetch();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="header">
		<?php if(isset($_SESSION['userID'])): ?>
		<div id="userLogin">
			<h4>Welcome, <a href="profile.php?userID=<?= $_SESSION['userID'] ?>"><?= $fname ?></a>!</h4>
			<p><a href="logout.php">Logout</a></p>
		</div>
		<?php endif ?>
		<h1>Memebook</h1>
	</div>
	<div id="navbar">
    	<nav>
   	      	<ul>
	      		<li><a href="index.php">Home</a></li>
	        	<?php if(isset($_SESSION['userID'])): ?>
	        		<li><a href="createP.php">Post MEME</a></li>
					<?php if($user['userID'] == $userID && $user['userType'] == "Admin"): ?>
						<li><a href="allUsers.php">All Registered Accounts</a></li>
					<?php endif ?>
				<?php else: ?>
					<li><a href="register.php">Register</a></li>
					<li><a href="login.php">Login</a></li>
				<?php endif ?>
	      	</ul>
    	</nav>
  	</div><br>
	<div id="content">
		<form id="search" method="post" action="search.php">
    		<input type="text" name="searchContent" placeholder="Search by title/profile">
    		<input id='search' type='submit' name="search" value="Search">
    	</form>
    	<div>
    		<nav id="sortNavbar">
    			<h4>Sort by:</h4>
    			<ul>
    				<li><a href="?orderby=PTitle&order=<?= $titleOrder ?>">Title</a></li>
    				<li><a href="?orderby=PDate&order=<?= $dateOrder ?>">Date</a></li>
    				<li><a href="?orderby=poster&order=<?= $userOrder ?>">Name</a></li>
    				<li><a href="?orderby=imageP&order=<?= $imageOrder ?>">Image</a></li>
    			</ul>
    		</nav>
    	</div>
    	<h2>All that Delicious MEMEs</h2>
		<?php while($row = $statement->fetch()): ?>	
			<div id="memes">
				<div>
					<h4><a href="profile.php?userID=<?= $row['userId'] ?>"><?= $row['Poster'] ?></a></h4>
				</div>
				<div>
					<h5><?= $row['PTitle'] ?></h5>
					<p><?= date("F d, Y, g:i a", strtotime($row['PDate'])); ?></p>
				</div>
				<p><?= substr($row ['PContent'],0,200) ?>
				<?php if(isset($_SESSION['userID'])): ?>
						<?php if($user['userType'] == "Admin"): ?>
							- <a href="updateP.php?id=<?= $row['PostId'] ?>">Edit</a></p>
						<?php elseif($row['userId'] == $userID && $user['userType'] != "Admin"): ?>
							- <a href="updateP.php?id=<?= $row['PostId'] ?>">Edit</a></p>
						<?php else: ?>
							</p>
						<?php endif ?>
				<?php endif ?>
				<?php if($row['imageP'] != ''): ?>
					<?php $new_image_path = dirname(__FILE__) . '\images\\' . $row['imageP']; ?>
					<br><img src="images/<?= $row['imageP'] . '_medium' . '.' . pathinfo($new_image_path, PATHINFO_EXTENSION) ?>" alt="<?= $row['imageP'] ?>">
				<?php endif ?> 
					<div id="commentsDiv">						
						<?php $commentQuery = "SELECT * FROM comments WHERE postId = '$row[PostId]'"; ?>
						<?php $statementC = $db->prepare($commentQuery); ?>
						<?php $statementC->execute();	?>
						<h5>Comments</h5>
						<?php while($rowC = $statementC->fetch()): ?>
							<p><?= $rowC['CContent'] ?></p>
							<p id="submitted">Submitted by: <?= $rowC['commenter'] ?>
								<?php if($user['userType'] == "Admin"): ?>
									- <a href="updateComment.php?id=<?= $rowC['CommentsId'] ?>">Edit</a></p>
								<?php elseif($rowC['userId'] == $userID && $user['userType'] != "Admin"): ?>
									- <a href="updateComment.php?id=<?= $rowC['CommentsId'] ?>">Edit</a></p>
								<?php else: ?>
									</p>
								<?php endif ?>
						<?php endwhile ?>
					</div>
				<?php if(isset($_SESSION['userID'])): ?>
					<a href="createComment.php?postID=<?= $row['PostId'] ?>">Add a Comment</a>
				<?php endif ?>
			</div><br>
		<?php endwhile ?>
	</div>
</body>
</html>