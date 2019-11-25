<?php
	require('connect.php');

	$query = "SELECT * FROM posts WHERE PostId = '$_GET[id]'";
	$statement = $db->prepare($query);
	$statement->execute();  

	//update post
	if(isset($_POST['update']))
	{
		//delete image from post
		if($_POST['btnDeleteImage'] == 'Click')
		{
			$queryDelete = "UPDATE posts SET imageP = NULL WHERE PostId = '$_GET[id]'";
			$statementD = $db->prepare($queryDelete);
			$statementD->execute();

			$statement->bindValue(':id', $id , PDO::PARAM_INT);

			$row = $statement->fetch();
			$new_image_path = dirname(__FILE__) . '\images\\' . $row['imageP'];

			//delete files
			unlink('images/' . $row['imageP']);
			unlink('images/' . $row['imageP'] . '_medium' . '.' . pathinfo($new_image_path, PATHINFO_EXTENSION));
		}

		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		if((strlen($title) > 0) && (strlen($content) > 0))
		{
			$query = "UPDATE posts SET PTitle ='$_POST[title]', PContent = '$_POST[content]'
			WHERE PostId = '$_GET[id]' ";  

			$statement = $db->prepare($query);
			$statement->bindValue(':title', $title);
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

	if(isset($_POST['deleteImage']))
	{

	}

	if(isset($_POST['delete']))
	{
		$row = $statement->fetch();
		$new_image_path = dirname(__FILE__) . '\images\\' . $row['imageP'];

		//delete files
		unlink('images/' . $row['imageP']);
		unlink('images/' . $row['imageP'] . '_medium' . '.' . pathinfo($new_image_path, PATHINFO_EXTENSION));

		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		$query = "DELETE FROM posts WHERE PostId = '$_GET[id]'";  
		$statement = $db->prepare($query);
		$statement->bindValue(':title', $title);
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

	if(!isset($_GET['id']) ||   ($_GET['id']) < 1 || (!is_numeric($_GET['id'])))
	{
		call();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php while($row = $statement->fetch()): ?>
	<title><?= $row['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h1>Edit Memes</h1>
	<div>
		<form method="post">
			<div>
				<label for="title">Meme Title</label><br>
				<input value= '<?= $row['PTitle']?>' id='title' name='title'><br><br>
				<label for="content">Meme Content</label><br>  
				<textarea name='content' COLS='90' ROWS='10'><?= $row['PContent']?></textarea>
			</div>
			<div>
				<?php if($row['imageP'] != null): ?>
					<div>
						<?php $new_image_path = dirname(__FILE__) . '\images\\' . $row['imageP']; ?>
						<img src="images/<?= $row['imageP'] . '_medium' . '.' . pathinfo($new_image_path, PATHINFO_EXTENSION) ?>" alt="<?= $row['imageP'] ?>"><br>
						<label for="btnDeleteImage">Delete Image?</label>
						<input type="checkbox" name="btnDeleteImage" value="Click">
						<label>(ie. To delete an image from a post. Click the checkbox and click Update Post.)</label>
					</div><br>
				<?php endif ?>
				<input id='update' type='submit' name='update' value='Update Post' onclick = "return confirm('Update your post?')">
				<input id='submit' name='delete' type='submit' value='Delete Post' onclick = "return confirm('Delete your post?')">
			</div>
		</form>
	</div><br>
	<div>		
		<p><a href="index.php">Go Back</a></p>
	</div>
</body>
	<?php endwhile ?>
</html>