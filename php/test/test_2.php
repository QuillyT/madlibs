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
  
  /* randomly select player */
  //NOTE: ORDER BY RAND() IS VERY SLOW?... MUST FIND BETTER WAY 
  //...IF THERE AREN'T ANY HOLES, SELECT RANDOM VALUE AHEAD OF TIME?
  
  $rando = $mysqli->query("SELECT * FROM users WHERE userID <> $p1 ORDER BY RAND() LIMIT 1");  
  $rando_row = $rando->fetch_array(MYSQLI_ASSOC);
  $rando_id = $rando_row["userID"];
  $p2 = intval($rando_id);    
  
  
  //search for existing pair of the players
  $pair = $mysqli->query("SELECT * FROM pairs WHERE (player1 = $p1 AND player2 = $p2) OR (player1 = $p2 AND player2 = $p1)");
    
  if($pair->num_rows > 0){
    //pair exists, return pairID
    $pair_row = $pair->fetch_array(MYSQLI_ASSOC);    
    printf ("{pairID:%s, player:{ID:%s,name:%s,email:%s}}",$pair_row["pairID"],$rando_row["userID"],$rando_row["name"],$rando_row["email"]);
    $pair->free();
  }else{
    //pair doesn't exist
    //create pair     
    $mysqli->query("INSERT INTO pairs (player1, player2) VALUES ('$p1', '$p2')");

    //find pair
    $new_pair = $mysqli->query("SELECT * FROM pairs WHERE (player1 = $p1 AND player2 = $p2) OR (player1 = $p2 AND player2 = $p1)");
    if($new_pair->num_rows>0){
      //return pairID
      $new_pair_row = $new_pair->fetch_array(MYSQLI_ASSOC);
      printf ("{pairID:%s, player:{ID:%s,name:%s,email:%s}}",$new_pair_row["pairID"],$rando_row["userID"],$rando_row["name"],$rando_row["email"]);
      $new_pair->free();
    }else{
      //can't find pair
      print "Error creating/finding pair";
    }
  }
  $mysqli->close();
?>