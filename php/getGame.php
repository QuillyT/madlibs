<?php  
  include('config.php');
  
  $q = $_GET['gameID'];  
  $query = "SELECT games.gameID, games.turn, games.inputs, games.isComplete, stories.story, stories.blanks, games.player1, users1.name AS playerA, users2.name as playerB FROM games JOIN pairs ON games.pairID = pairs.pairID JOIN stories ON games.storyID = stories.storyID LEFT JOIN users AS users1 ON users1.userID = pairs.playerA LEFT JOIN users AS users2 ON users2.userID = pairs.playerB WHERE games.gameID = $q";
  $game = $mysqli->query($query); 
  
  if(!$game){
    die('ERR 14 Getting game.');
  }
  if($game->num_rows==0){
    die('No games. Start one!');
  }
  $game_row = $game->fetch_array(MYSQLI_ASSOC);
  printf('{"gameID":%s,"turn":%s,"inputs":"%s","isComplete":%s,"story":"%s","blanks":%s,"player1":"%s","playerA":"%s","playerB":"%s"}',$game_row['gameID'],$game_row['turn'],$game_row['inputs'],$game_row['isComplete'],$game_row['story'],$game_row['blanks'],$game_row['player1'],$game_row['playerA'],$game_row['playerB']);
  $game->free();    
  $mysqli->close();
?>