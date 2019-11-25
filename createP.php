<?php
  require('connect.php');
  include 'D:\XAMPP\htdocs\WD2\Final Project\php-image-resize-master\lib\ImageResize.php';
  use \Gumlet\ImageResize;

  session_start();

  $userId = $_SESSION['userID'];
  $fname = $_SESSION['fname'];

  if($_POST && isset($_POST['title']) && isset($_POST['content'])) 
  {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image_name = $_FILES['image']['name'];

    if((strlen($title) > 0) && (strlen($content) > 0))
    { 
      $query = "INSERT INTO posts (PTitle, PContent, imageP, userId, Poster) VALUES (:title, :content, :image_name, :userId , :poster)";
      $statement = $db->prepare($query);

      $statement->bindValue(':title', $title);
      $statement->bindValue(':content', $content);
      $statement->bindValue(':image_name', $image_name);
      $statement->bindValue(':userId', $userId);
      $statement->bindValue(':poster', $fname);

      $statement->execute();
      
      header("Location:index.php");      
    }
    else
    {
       header("Location:error.php");
    }           
  }

///////////////////////////////////////// IMAGE UPLOAD ///////////////////////////////////////////////////////////
  $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
  $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
  
  if($image_upload_detected)
  {
    $image_filename = $_FILES['image']['name'];
    $temp_image_path = $_FILES['image']['tmp_name'];
    $new_image_path = file_upload_path($image_filename);
    //resize_image($image_filename);

    if (file_is_an_image($temp_image_path, $new_image_path)) 
    {
      if(move_uploaded_file($temp_image_path, $new_image_path))
      {
        $image = new ImageResize($new_image_path);
        $image -> resizeToWidth(500);
        $image -> save($new_image_path . '_medium.' . pathinfo($new_image_path, PATHINFO_EXTENSION));
      }
    }
    else
    {
      header("Location:error.php");
    }
  }

  //build the new file path for the image
  function file_upload_path($original_filename, $upload_subfolder_name = 'images')
  {
    $current_folder = dirname(__FILE__);

    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];

    return join(DIRECTORY_SEPARATOR, $path_segments);
  }

  //check if image has valid file ext
  function file_is_an_image($temporary_path, $new_path)
  {
    $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_ext = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_ext = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = getimagesize($temporary_path)['mime'];

    $file_ext_valid = in_array($actual_file_ext, $allowed_file_ext);
    $mime_type_valid = in_array($actual_mime_type, $allowed_mime_types);

    return $file_ext_valid && $mime_type_valid;
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Meme</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div>
    <h1>What's up Doc?</h1>
  </div>
  <div>
    <form method="post" action="createP.php" enctype="multipart/form-data">
      <label for="title">Meme Title</label><br>
      <input id='title' name='title'><br><br>
      <label for="content">Meme Content</label><br>
      <textarea name='content' COLS='90' ROWS='10'></textarea><br>
      <label for="image">Upload Some Goodies:</label>
      <input type="file" name="image" id="image"><br>
      <input id='submit' type='submit'>
    </form>
  </div>
  <div>   
    <p><a href="index.php">Go Back</a></p>
  </div>
</body>
</html>