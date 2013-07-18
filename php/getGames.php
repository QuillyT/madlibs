<?php  
  include('config.php');
  
  $q = $_GET['userID'];  
  //get all pairs with ID in player 1 or player 2
  $games = $mysqli->query("SELECT games.gameID, games.turn, games.inputs, games.isComplete, stories.story, stories.blanks, games.player1, users1.name AS playerA, users2.name as playerB FROM games JOIN pairs ON games.pairID = pairs.pairID JOIN stories ON games.storyID = stories.storyID LEFT JOIN users AS users1 ON users1.userID = pairs.playerA LEFT JOIN users AS users2 ON users2.userID = pairs.playerB WHERE (pairs.playerA = $q OR pairs.playerB = $q)");
  if(!$games){
    die('ERR 12 Finding games involving user.');
  }
    
  printf("[");
  $index = $games->num_rows;
  while($games_row = $games->fetch_array(MYSQLI_ASSOC)){
    printf('{"gameID":%s,"turn":%s,"inputs":"%s","isComplete":%s,"story":"%s","blanks":%s,"player1":"%s","playerA":"%s","playerB":"%s"}',$games_row['gameID'],$games_row['turn'],$games_row['inputs'],$games_row['isComplete'],$games_row['story'],$games_row['blanks'],$games_row['player1'],$games_row['playerA'],$games_row['playerB']);
    if($index--!=1){
      printf(",");
    }    
  }
  printf("]");  
  $games->free();
  $mysqli->close();
?>
