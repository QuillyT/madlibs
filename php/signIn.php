<?php  
  include('config.php');
  
  $q1 = $_GET['email'];
  $q2 = $_GET['password'];
    
  $query = "SELECT * FROM `users` WHERE (email = '$q1' AND password = '$q2') LIMIT 0, 1 ";
  $result = $mysqli->query($query); 
  if(!$result){
    die('ERR 3 Checking if account exists');
  }
  if($result->num_rows==0){     
    $email = $mysqli->query("SELECT * FROM `users` WHERE email = '$q1' LIMIT 0, 1 ");
    if($email->num_rows==0){
        die("Account with this email doesn't exist.");
    }
    die("Password doesn't match");
  }  
  $row = $result->fetch_array(MYSQLI_ASSOC);
  printf ('{"userID":%s,"name":"%s","email":"%s"}', $row["userID"], $row["name"], $row["email"]);
  $result->free();    
  $mysqli->close();
?>
