<?php  
  include('config.php');
  
  $p1 = intval($_GET['userID']);
  $p2 = intval($_GET['playerID']);
    
  //search for existing pair of the players
  $pair = $mysqli->query("SELECT * FROM pairs WHERE (playerA = $p1 AND playerB = $p2) OR (playerA = $p2 AND playerB = $p1)");
  if(!$pair){
    die('ERR 5 Checking if pair exists.');
  }
  
  if($pair->num_rows==0){
    //no pair exists - add a pair   
    $new_pair = $mysqli->query("INSERT INTO pairs (playerA, playerB) VALUES ($p1, $p2)");
    if(!$new_pair){
      die('ERR 6 Adding new pair.');
    }    
    printf ('{"pairID":%s}',$mysqli->insert_id);    
  }
  else{
    $pair_row = $pair->fetch_array(MYSQLI_ASSOC);    
    printf ('{"pairID":%s}',$pair_row["pairID"]);
    $pair->free();
  }    
  $mysqli->close();
?>