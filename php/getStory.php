<?php  
  include('config.php');
  
  $q = $_GET['storyID'];
  $query = "SELECT * FROM `stories` WHERE `storyID` = '$q' LIMIT 0, 1 ";
  $story = $mysqli->query($query); 
  if(!$story){
    die('ERR 9 Getting story');
  }
  
  $story_row = $story->fetch_array(MYSQLI_ASSOC);
  printf ('{"storyID":%s,"story":"%s","blanks":%s}', $story_row["storyID"], $story_row["story"], $story_row["blanks"]);
  $story->free();    
  $mysqli->close();
?>
