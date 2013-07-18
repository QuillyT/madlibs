<?php
  //Connection's Parameters
  /*******************/
  $db_host="localhost";
  $db_name="quenti10_madlib";
  $username="quenti10_mad";
  $password="madlibs";
  /*******************/
  
  /*******************
  $db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";  
  /*******************/

  $mysqli = new mysqli($db_host, $username, $password, $db_name);

  /* check connection */
  if ($mysqli->connect_errno) {
    exit("ERR 0 " . mysqli_connect_error());
  }
?>