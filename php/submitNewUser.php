<?php  
  include('config.php');
  
  $q1 = $_GET['name'];
  $q2 = $_GET['email'];
  $q3 = $_GET['password'];
  
  /* check if email exists */  
  $users = $mysqli->query("SELECT * FROM users WHERE email = '$q2' OR name = '$q1' LIMIT 0, 1 ");
  if(!$users){
    die('ERR 1 Checking if user exists.');
  }
  
  if($users->num_rows > 0){
    $user_row = $users->fetch_array(MYSQLI_ASSOC);
    if($user_row['email']==$q2){
      print "EMAIL EXISTS.";
    }
    if($user_row['name']==$q1){
      print " NAME EXISTS.";
    }    
    $users->free();
  }
  else{
    /* submit user */
    $query = "INSERT INTO users (name, email, password) VALUES ('$q1','$q2','$q3');";
    $user = $mysqli->query($query);
    if(!$user){
      die('ERR 2 Creating new user.');
    }    
    printf ("%s",$mysqli->insert_id);
  }
  $mysqli->close();
?>