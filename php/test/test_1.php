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
  
  $q1 = 'asdf';
  $q2 = 'asdf@asdf.com';
  $q3 = 'asdfasdf';
  
  /* check if email exists */  
  $result = $mysqli->query("SELECT * FROM users WHERE email = '$q2' LIMIT 0, 1 ");  
  if($result->num_rows > 0){
    $result->free();
    print "ERR: EMAIL EXISTS";    
  }
  else{
    /* submit user */
    $query = "INSERT INTO users (name, email, password) VALUES ('$q1','$q2','$q3');";
    $mysqli->query($query);

    /* check if new entry exists */  
    $result1 = $mysqli->query("SELECT * FROM users WHERE email = '$q2' LIMIT 0, 1 ");  
    if($result1->num_rows > 0){
      $row = $result1->fetch_array(MYSQLI_ASSOC);
      printf ("%s %s %s %s", $row["userID"], $row["name"], $row["email"], $row["password"]);
      $result1->free();
    }
    else{
      print "ERR: I DON'T KNOW";
    }
  }
  $mysqli->close();
?>