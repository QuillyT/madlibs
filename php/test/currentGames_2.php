<?php  
  include('config.php');
  
  $q = $_GET['userID'];
  
  //get all pairs with ID in player 1 or player 2
  $games = $mysqli->query("SELECT * FROM games JOIN pairs ON pairs.player1 = $q OR pairs.player2 = $q WHERE games.pairID = pairs.pairID AND games.isComplete = 0");
  if(!$games){
    die('ERR 12 Finding games involving user.');
  }
    
  printf("[");
  $index = $games->num_rows;
  while($games_row = $games->fetch_array(MYSQLI_ASSOC)){
    printf('{"gameID":%s,"storyID":%s,"pairID":%s,"turn":%s,"inputs":"%s","player1":%s}',$games_row['gameID'],$games_row['storyID'],$games_row['pairID'],$games_row['turn'],$games_row['inputs'],$games_row['player1']);
    if($index--!=1){
      printf(",");
    }    
  }
  printf("]");  
  $games->free();
  $mysqli->close();
?>
