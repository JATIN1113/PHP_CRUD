<?php
include 'connection.php';
   if(isset($_GET['id'])){
     $id = $_GET['id'];

     $sql = "update employee_info set is_deleted = CURRENT_TIMESTAMP where id=$id ;";
     $stmt = $conn->prepare($sql);
     $stmt->execute();
     if($stmt==false){
      echo "Prepare failed";
     }
     $stmt->bind_param("i",$id);

     if($stmt->execute()){
      echo "Result Deleted Successfully!!";
      header("Location: index.php");
     }



   //   $result = mysqli_query($conn,$sql);
   //   if($result){
   //      echo "Deleted Successfully!!!";
   //   }else{
   //      die("Connection Failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
   //   }
   } else{
    echo "No phone number provided";
   }



?>