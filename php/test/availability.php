<?php  
  $db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";

  $mysqli = new mysqli($db_host, $username, $password, $db_name);
  
  /* check connection */
  if ($mysqli->connect_errno) {     
      echo "server error";
      exit();
  }
  
  $q = $_GET['name'];   
  
  if ($result = $mysqli->query("SELECT * FROM `users` WHERE `userName` = '$q' LIMIT 0, 30 ")) {
      if(empty($result)){
        echo '0';
      }
      else{
        echo '1';
      }
      /* free result set */
      $result->close();
  }
  $mysqli->close();
?>
