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
  
  $p1 = intval($_GET['userID']);
  $p2 = intval($_GET['playerID']);
    
  //search for existing pair of the players
  $pair = $mysqli->query("SELECT * FROM pairs WHERE (player1 = $p1 AND player2 = $p2) OR (player1 = $p2 AND player2 = $p1)");
    
  if(!$pair){
    die('Invalid query: ' . mysql_error());
  }
  if($pair->num_rows==0){
    //no pair exists
    $new_pair = $mysqli->query("INSERT INTO pairs (player1, player2) VALUES ('$p1', '$p2')");
    
    //insert failed
    if(!$new_pair){
      die('Invalid query: ' . mysql_error());
    }
    
    //find pair
    $result = $mysqli->query("SELECT * FROM pairs WHERE (player1 = $p1 AND player2 = $p2) OR (player1 = $p2 AND player2 = $p1)");
    if(!$result){
      die('Invalid query: ' . mysql_error());
    }
    $pair_row = $result->fetch_array(MYSQLI_ASSOC);
    printf ('{"pairID":%s}',$pair_row["pairID"]);
    $result->free();    
  }
  else{
    $pair_row = $pair->fetch_array(MYSQLI_ASSOC);    
    printf ('{"pairID":%s}',$pair_row["pairID"]);
    $pair->free();
  }    
  $mysqli->close();
?>