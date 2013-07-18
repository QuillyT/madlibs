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
  
  $q = $_GET['pairID'];
  
  //get all pairs with ID in player 1 or player 2
  $pairs = $mysqli->query("SELECT * FROM pairs WHERE pairID = $q");
  if(!$pairs){
    die('ERR 13 Finding pair.');
  }
      
  $index = $pairs->num_rows;
  while($games_row = $pairs->fetch_array(MYSQLI_ASSOC)){
    printf('{"pairID":%s,"player1":"%s","player2":"%s"}',$pair_row[],$player1[],$player2["user"]);
    if($index--!=1){
      printf(",");
    }    
  }
  
  $pairs->free();
  $mysqli->close();
?>
