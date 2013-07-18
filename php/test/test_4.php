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
  
  $q = 'qwer';
  $query = "SELECT * FROM `users` WHERE `email` = '$q' LIMIT 0, 1 ";
  $result = $mysqli->query($query); 
  
  if(!$result){
    die('ERR 3 Checking account exists');
  }
  
  if($result->num_row>0){
    $row = $result->fetch_array(MYSQLI_ASSOC);
    printf ("%s %s %s", $row["userID"], $row["name"], $row["password"]);
    $result->free();    
  }
  
  $mysqli->close();
?>
