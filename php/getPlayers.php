<?php  
  include('config.php');
    
  $query = "SELECT users.userID,users.name FROM users";
  $users = $mysqli->query($query); 
  
  if(!$users){
    die('ERR 14 Getting players.');
  }
  if($users->num_rows==0){
    die('No players. Invite your friends!');
  }
  
  printf("[");
  $index = $users->num_rows;
  while($user_row = $users->fetch_array(MYSQLI_ASSOC)){
    printf('{"userID":%s,"name":"%s"}',$user_row['userID'],$user_row['name']);
    if($index--!=1){
      printf(",");
    }    
  }
  printf("]");  
  
  
  $users->free();    
  $mysqli->close();
?>