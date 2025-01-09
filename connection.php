<?php session_start(); ?>


<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

   $host = "localhost";
   $username = "root";
   $db_password = "root";
   $db_name = "client_details";

   $conn = new mysqli($host,$username,$db_password,$db_name);
   // if($conn->connect_error){
   //  die("Connection Failed: ".$conn->connect_error);
   // }else{
    
   // }

   if ($conn->connect_error) {
      die("Connection Failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
  }else{
   // echo "Connected to database";
  }

?>
