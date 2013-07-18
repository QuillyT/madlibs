<?php  
  $db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";

  $mysqli = new mysqli($db_host, $username, $password, $db_name);
  
  /* check connection */
  if ($mysqli->connect_errno) {
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
  }
  
  $pair_id = 35;
  
  //randomly select storyID
  $story = $mysqli->query("SELECT * FROM stories ORDER BY RAND() LIMIT 1");  
  if(!$story){
    die('ERR 6 Choosing random story.');
  }
  $story_row = $story->fetch_array(MYSQLI_ASSOC);  
  $story_id = $story_row['storyID'];  
  
  $new_game = $mysqli->query("INSERT INTO games (`gameID`, `storyID`, `pairID`, `turn`, `inputs`) VALUES (NULL, $story_id, $pair_id, 0, '')");  
  
  if(!$new_game){
    die('ERR 7 Creating game.');
  }
  
  printf ('{"gameID":%s,"storyID":%s}',$mysqli->insert_id,$story_id);
    
  $mysqli->close();
?>