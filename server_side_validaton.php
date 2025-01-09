<?  include 'connection.php';

   $name=$company=$title=$email=$phone=$work_hours=$info=$gender=$password=$password='';
   $nameErr=$companyErr=$titleErr=$emailErr=$phoneErr=$work_hoursErr=$infoErr=$genderErr=$passwordErr=$cpasswordErr='';

 

 function test_input($data){
    $data= trim($data);
    $data= stripslashes($data);
    $data= htmlspecialchars($data);
    return $data;
 }



//  if (isset($_POST['submit'])) {
//    $client_name = $_POST['name'];
//    $company = $_POST['company'];
//    $title = $_POST['title'];
//    $email = $_POST['email'];
//    $phone = $_POST['phone'];
//    $work_hours = $_POST['work_hours'];
//    $info = $_POST['info'];
//    $gender = $_POST['gender'] ?? 'not specified';
//    $password = $_POST['password'];
//    $cpassword = $_POST['cpassword'];

//    $sql = "INSERT INTO client_info (name, company, title, gender, email, phone, working_hours, additional_info, password, confirm_password) 
//            VALUES ('$client_name', '$company', '$title', '$gender', '$email', '$phone', '$work_hours', '$info', '$password', '$cpassword')";

//    $result = mysqli_query($conn, $sql);
//    if ($result) {
//       //  echo "Data inserted successfully";
//       header('Location: display.php');
//    } else {
//        die("Error: " . mysqli_error($conn));
//    }
// }

   //  if(isset($_POST['submit'])){
   //    $name = $_POST['name'];
   //    $company = $_POST['company'];
   //    $title = $_POST['title'];
   //    $email = $_POST['email'];
   //    $phone = $_POST['phone'];
   //    $work_hours = $_POST['work_hours'];
   //    $info = $_POST['info'];
   //    $gender = $_POST['gender'] ?? 'not specified';
   //    $password = $_POST['password'];
   //    $cpassword = $_POST['cpassword'];


   //    $sql = "INSERT INTO client_info (name, company, title, gender, email, phone, working_hours, additional_info, password, confirm_password) 
   //            VALUES (:name, :company, :title, :gender, :email, :phone, :work_hours, info, password, cpassword);";

   //    $stmt = $conn->prepare($sql);

   //    $stmt->bind_param(":name",$name);
   //    $stmt->bind_param(":company",$company);
   //    $stmt->bind_param(":title",$title);
   //    $stmt->bind_param(":gender",$gender);
   //    $stmt->bind_param(":email",$email);
   //    $stmt->bind_param(":phone",$phone);
   //    $stmt->bind_param(":work_hours",$work_hours);
   //    $stmt->bind_param(":info",$info);
   //    $stmt->bind_param(":password",$password);
   //    $stmt->bind_param(":cpassword",$cpassword);


   //    $stmt->execute([$name,$company,$title,$gender,$email,$phone,$work_hours,$info,$password,$cpassword]);

   //    header("Location: index.php");
   //    die();
   //  }else{
   //    // header("Location: index.php");
   //    echo "Invalid request method.";
   //  }


   


   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = true;

    // Sanitize and validate inputs
    $name = test_input($_POST['name']);
    if (empty($name)) {
        $nameErr = "Please enter your name";
        $valid = false;
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Only letters and white space allowed";
        $valid = false;
    }

    $company = test_input($_POST['company']);
    if (empty($company)) {
        $companyErr = "Please enter your company name";
        $valid = false;
    }

    $title = test_input($_POST['title']);
    if (empty($title)) {
        $titleErr = "Please enter your title";
        $valid = false;
    }

    $email = test_input($_POST['email']);
    if (empty($email)) {
        $emailErr = "Please enter your email";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $valid = false;
    } else {
        $stmt = $conn->prepare("SELECT id FROM employee_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $emailErr = "Email already exists";
            $valid = false;
        }
        $stmt->close();
    }

    $phone = test_input($_POST['phone']);
    if (empty($phone)) {
        $phoneErr = "Please enter your phone number";
        $valid = false;
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phoneErr = "Invalid phone number format";
        $valid = false;
    }

    $work_hours = test_input($_POST['work_hours']);
    if (empty($work_hours)) {
        $work_hoursErr = "Please enter your working hours";
        $valid = false;
    } elseif (!preg_match("/^\d+(\.\d{1,2})?$/", $work_hours)) {
        $work_hoursErr = "Please enter a valid number of working hours";
        $valid = false;
    }

    $gender = test_input($_POST['gender'] ?? '');
    if (empty($gender)) {
        $genderErr = "Please select a gender";
        $valid = false;
    }

    $password = test_input($_POST['password']);
    $cpassword = test_input($_POST['cpassword']);
    if (empty($password) || empty($cpassword)) {
        $passwordErr = "Please enter a password";
        $cpasswordErr = "Please confirm your password";
        $valid = false;
    } elseif (strlen($password) < 8) {
        $passwordErr = "Your password must contain at least 8 characters";
        $valid = false;
    } elseif ($password !== $cpassword) {
        $cpasswordErr = "Passwords do not match";
        $valid = false;
    }

    $info = test_input($_POST['info']);

    // Insert data into the database if valid
    if ($valid) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO employee_info (name, company, title, gender, email, phone, working_hours, additional_info, password,confirm_password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssisss", 
            $name, $company, $title, $gender, $email, $phone, $work_hours, $info, $password, $cpassword
        );
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}



?>