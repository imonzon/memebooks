<?php
	require('connect.php');

	$query = "SELECT * FROM posts";
	$statement = $db->prepare($query);
	$statement->execute();

	$query1 = "SELECT * FROM users";
	$statement1 = $db->prepare($query1);
	$statement1->execute();

	if($_POST && isset($_POST['searchContent']) && strlen($_POST['searchContent']) > 0)
	{
		$search = filter_input(INPUT_POST, 'searchContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	}
	else
	{
		header("refresh:0.15; url=index.php");
		echo '<script language ="javascript">';
		echo 'alert("Input must have value")';
		echo '</script>';
	}

	$postCount = 0;
	$userCount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Search Results</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h2>Search results</h2>
	<div>
		<div id="postResult">
			<h5>Delicious Meme Titles:</h5>
			<?php while($post= $statement->fetch()): ?>
				<?php if((strpos(strtolower($post['PTitle']), strtolower($search)) !== false)): ?>
					<p><a href="trunc.php?userID=<?= $post['postId'] ?>"><?= $post['PTitle'] ?></a></p>
				<?php $postCount = $postCount + 1; ?>
				<?php endif ?>
			<?php endwhile ?>
			<?php if($postCount == 0): ?>
				<p>No Memes Found :( </p>
			<?php endif ?>
		</div>
		<div id="userResult">
			<h5>Profiles:</h5>
			<?php while($user = $statement1->fetch()): ?>	
				<?php if((strpos(strtolower($user['fname']), strtolower($search)) !== false)): ?>
					<p><a href="profile.php?userID=<?= $user['userID'] ?>"><?= $user['fname'] ?></a></p>
				<?php $userCount = $userCount + 1; ?>
				<?php endif ?>
			<?php endwhile ?>
			<?php if($userCount == 0): ?>
				<p>No Users Found</p>
			<?php endif ?>
		</div>
	</div><br><br>
	<footer>
		<p>Go Back to the <a href="index.php">Home Page</a></p>
	</footer>
</body>
</html>