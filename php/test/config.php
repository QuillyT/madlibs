<?php
// Connection's Parameters
$db_host="localhost";
$db_name="quenti10_madlib";
$username="quenti10_mad";
$password="madlibs";

$db_host="localhost";
  $db_name="project_madlibs";
  $username="root";
  $password="drow";

$db_con=mysql_connect($db_host,$username,$password);
$connection_string=mysql_select_db($db_name);
// Connection
mysql_connect($db_host,$username,$password);
mysql_select_db($db_name);
?>