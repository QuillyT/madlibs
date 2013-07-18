<?php  
  include('config.php');
  
  $q1 = $_GET['gameID'];
  $q2 = $_GET['turn'];
  $q3 = $_GET['inputs'];
  $q4 = $_GET['isComplete'];
  
  /* check if email exists */  
  $results = $mysqli->query("UPDATE games SET games.turn=$q2, games.inputs='$q3', games.isComplete=$q4 WHERE games.gameID=$q1");
  if(!$results){
    die('ERR 13 Submitting game inputs.');
  }
  print 'GAME SUBMITTED';
  $mysqli->close();
?>