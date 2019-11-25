<?php
	require('connect.php');

	$query = "SELECT * FROM comments WHERE CommentsId = '$_GET[id]'";
	$statement = $db->prepare($query);
	$statement->execute(); 

	//update post
	if(isset($_POST['update']))
	{
		$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		if(strlen($content) > 0)
		{
			$query = "UPDATE comments SET CContent = '$content' WHERE CommentsId = '$_GET[id]'";  

			$statement = $db->prepare($query);
			$statement->bindValue(':content', $content);
			$statement->bindValue(':id', $id , PDO::PARAM_INT);

			$statement->execute();

			header("Location:index.php");
		}
		else
		{
			header("Location:error.php");
		}
	}

	if(isset($_POST['delete']))
	{
		$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "DELETE FROM comments WHERE CommentsId = '$_GET[id]'";  
		$statement = $db->prepare($query);
		$statement->bindValue(':content', $content);
		$statement->bindValue(':id', $id , PDO::PARAM_INT);

		$statement->execute();

		header("Location:index.php");
	}

	function call()
	{
		header("Location:index.php");
		exit;
	}

	if(!isset($_GET['id']) || ($_GET['id']) < 1 || (!is_numeric($_GET['id'])))
	{  
		call();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php while($row = $statement->fetch()): ?>
	<title>Comments</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Edit Comment</h1>
	<div>
		<form method="post">
			<div>
				<label for="content">Edit Comment:</label><br>  
				<textarea name='content' COLS='90' ROWS='10'><?= $row['CContent']?></textarea>
			</div>
			<div>
				<input id='update' type='submit' name='update' value='Update Comment' onclick = "return confirm('Update your comment?')">
				<input id='submit' name='delete' type='submit' value='Delete Comment' onclick = "return confirm('Delete your comment?')">
			</div>
		</form>
	</div><br>
	<div>		
		<p><a href="index.php">Go Back</a></p>
	</div>
</body>
	<?php endwhile ?>
</html>