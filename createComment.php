<?php
	require('connect.php');

	session_start();

	if(isset($_GET['postID']))
	{
		$_SESSION['postID']= $_GET['postID'];
	}

	$userId = $_SESSION['userID'];
	$fname = $_SESSION['fname'];
	$postId = $_SESSION['postID'];

  if($_POST && isset($_POST['content'])) 
  {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(strlen($content) > 0)
    {
    	if ($_POST["captcha_code"] == $_SESSION["captcha_code"])
    	{
		      $query = "INSERT INTO comments (userId, postId, commenter, CContent) VALUES (:userId, :postId, :commenter, :content)";
		      $statement = $db->prepare($query);

		      $statement->bindValue(':content', $content);
		      $statement->bindValue(':postId', $postId);
		      $statement->bindValue(':userId', $userId);
		      $statement->bindValue(':commenter', $fname);

		      $statement->execute();
		      
		      header("Location:index.php"); 
	     }  
	     else
	     {
	     	header("refresh:0.25; url=createComment.php?postID=" . $_SESSION['postID']); 
	     	echo '<script language ="javascript">';
			echo 'alert("Captcha does not match.")';
			echo '</script>';
	     }   
    }
    else
    {
        header("Location:error.php");
    }           
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Comment</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div>
    <h1>Add a Comment</h1>
  </div>
  <div>
    <form method="post" action="createComment.php">
      <label for="content">Comment:</label><br>
      <textarea name='content' COLS='90' ROWS='10'></textarea><br>
      <img src="captcha.php"/>
      <input type="text" name="captcha_code">
      <input id='submit' type='submit'>
    </form>
  </div>
  <div>   
    <p><a href="index.php">Go Back</a></p>
  </div>
</body>
</html>