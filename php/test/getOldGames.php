<?php  
  $db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";

  $mysqli = new mysqli($db_host, $username, $password, $db_name);
  
  /* check connection */
  if ($mysqli->connect_errno) {      
      exit();
  }
  
  $q = $_GET['userID'];
  
  //get all pairs with ID in player 1 or player 2
  $games = $mysqli->query("SELECT * FROM games JOIN pairs ON games.pairID = pairs.pairID JOIN stories ON games.storyID = stories.storyID WHERE (pairs.player1 = $q OR pairs.player2 = $q) AND games.isComplete = 1;");
  if(!$games){
    die('ERR 12 Finding games involving user.');
  }
    
  printf("[");
  $index = $games->num_rows;
  while($games_row = $games->fetch_array(MYSQLI_ASSOC)){
    printf('{"gameID":%s,"story":"%s","blanks":%s,"player1":"%s","player2":"%s","turn":%s,"inputs":"%s"}',$games_row['gameID'],$games_row['story'],$games_row['blanks'],$games_row['player1'],$games_row['player2'],$games_row['turn'],$games_row['inputs']);
    if($index--!=1){
      printf(",");
    }    
  }
  printf("]");  
  $games->free();
  $mysqli->close();
?>
