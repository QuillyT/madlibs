<?php  
  include('config.php');
  
  $user_name = $_GET['name'];
  $pair_id = $_GET['pairID'];
  
  //randomly select storyID
  $story = $mysqli->query("SELECT * FROM stories ORDER BY RAND() LIMIT 1");  
  if(!$story){
    die('ERR 6 Choosing random story.');
  }
  if($story->num_rows==0){
    die('ERR 7 No stories found.');
  }
  
  $story_row = $story->fetch_array(MYSQLI_ASSOC);  
  $story_id = $story_row['storyID'];  
  $story->free();
  
  $new_game = $mysqli->query("INSERT INTO games (`gameID`, `storyID`, `pairID`, `turn`, `inputs`, `player1`) VALUES (NULL, $story_id, $pair_id, 1, '', '$user_name');");
  
  if(!$new_game){
    die('ERR 8 Creating game.');
  }
  
  printf ('{"gameID":%s,"storyID":%s,"turn":1,"inputs":"","isComplete":0,"player1":"%s"}',$mysqli->insert_id,$story_id,$user_name);
  
  $mysqli->close();
?>