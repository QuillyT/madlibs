<?php  
  include('config.php');
  
  $p1 = intval($_GET['userID']);
  
  /* randomly select player */
  //NOTE: ORDER BY RAND() IS VERY SLOW?... MUST FIND BETTER WAY 
  //...IF THERE AREN'T ANY HOLES, SELECT RANDOM VALUE AHEAD OF TIME?
  
  $rando = $mysqli->query("SELECT * FROM users WHERE userID <> $p1 ORDER BY RAND() LIMIT 1");  
  if(!$rando){
    die('ERR 4 Getting random player.');
  }  
  if($rando->num_rows==0){
    die('NO OTHER PLAYERS');
  }
  $rando_row = $rando->fetch_array(MYSQLI_ASSOC);  
  printf ('{"userID":%s,"name":"%s","email":"%s"}',$rando_row["userID"],$rando_row["name"],$rando_row["email"]);
  $rando->free();
  $mysqli->close();
?>