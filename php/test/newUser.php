<?php  
  $db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";

  $mysqli = new mysqli($db_host, $username, $password, $db_name);
  
  /* check connection */
  if ($mysqli->connect_errno) {      
    print "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }
  
  $q1 = $_GET['name'];
  $q2 = $_GET['email'];
  $q3 = $_GET['password'];
  
  $query = "INSERT INTO users (name, email, password) VALUES ('$q1','$q2','$q3');";
  $mysqli->query($query);  
  $mysqli->close();
?>

