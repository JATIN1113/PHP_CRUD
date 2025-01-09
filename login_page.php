<?php session_start(); 
   if(isset($_SESSION['id'])){
    header('Location: index.php');
    exit();
  }
?>

<?php 
  include 'connection.php';
  
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $email_error = $password_error = "";
  $email = $password = $wrong = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true;
    if (empty($_POST["email"])) {
      $email_error = "Please enter an email address";
      $valid = false;
    } else {
      $email = test_input($_POST["email"]);
      if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address";
        $valid = false;
      }
    }
    if (!empty($_POST["password"])) {
      $password = test_input($_POST["password"]);
      if (strlen(trim($password)) < 8) {
        $password_error = "Your Password Must Contain At Least 8 Characters!";
        $valid = false;
      } elseif (!preg_match("#[0-9]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Number!";
        $valid = false;
      } elseif (!preg_match("#[A-Z]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Capital Letter!";
        $valid = false;
      } elseif (!preg_match("#[a-z]+#", $password)) {
        $password_error = "Your Password Must Contain At Least 1 Lowercase Letter!";
        $valid = false;
      }
    } else {
      $password_error = "Please enter a password";
      $valid = false;
    }
    if ($valid) {
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);

      $query = "SELECT * from employee_info WHERE email = '$email'";
      $user = mysqli_query($conn,$query);
      if (!$user) {
        header("HTTP/1.1 500 Internal Server Error");
        die('Query Failed');
      }
      $row = $user->fetch_assoc();
      if ($row && password_verify($password, $row['password'])) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        header('Location: index.php');
        exit();
      } else {
        header("HTTP/1.1 401 Unauthorized");
        $wrong= "Your email or password are incorrect.";
      }
    } 
  }

  function test_input($data)
  {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     
    p{
      text-align: center;
    }
    </style>
</head>
<body>

<section class="bg-light py-3 py-md-5 min-vh-100 d-flex justify-content-center align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <div class="card border border-light-subtle rounded-3 shadow-sm">
          <div class="card-body p-2 p-md-4">
            <div class="text-center mb-3 d-flex justify-content-center align-items-center" style="height:124px;">
              <a href="#!">
                <img src="logo.png" alt="BootstrapBrain Logo" width="216px" height="78px">
              </a>
            </div>
            <h2 class="fs-6 fw-normal text-center text-secondary position-relative">Sign in to your account</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validateLoginForm(event)" class="position-relative login_form" style="">
            <p class="text-center"><span class="error position-absolute login_span w-100 d-flex justify-content-center"><?php echo $wrong; ?></span></p>
              <div class="row gy-2 overflow-hidden">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="email" id="email" placeholder="" value="<?php echo htmlspecialchars($email); ?>" >
                    <label for="email" class="form-label">Email address</label>
                    <span id="email_error" class="error"><?php echo $email_error; ?></span>
                    <span class="error"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" name="password" id="password" value="<?php echo $password; ?>" placeholder="Password" >
                    <label for="password" class="form-label">Password</label>
                    <img src="eye-open.png" alt="" id="eye" class="position-absolute eye_icon" style="top:17px;height:27px;">
                    <span id="password_error" class="error"><?php echo $password_error; ?></span>
                    <span class="error"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex gap-2 justify-content-between cursor-pointer">
                    <div class="form-check remember_me cursor-pointer">
                      <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                      <label class="form-check-label text-secondary keep_logged" for="rememberMe">
                        Keep me logged in
                      </label>
                    </div>
                    <a href="#" class="link-primary text-decoration-none" id="forget_password">Forgot password?</a>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid my-3">
                    <button class="btn btn-primary btn-lg" type="submit">Log in</button>
                  </div>
                </div>
                <div class="col-12">
                  <p class="m-0 text-secondary text-center">Don't have an account? <a href="signup_page.php" class="link-primary text-decoration-none">Sign up</a></p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
        document.addEventListener('DOMContentLoaded', function () {
    let eye = document.querySelector("#eye");
    let password = document.querySelector("#password");

    eye.addEventListener('click', function () {
        if (password.type === 'password') {
            password.type = 'text';
            eye.src = 'eye-close.png';
        } else {
            password.type = 'password';
            eye.src = 'eye-open.png';
        }
    });
});



  let forget_password = document.querySelector("#forget_password");
  forget_password.addEventListener("click",()=>{
    alert("Forget password functionality coming soon!!");
  });

  
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      if (togglePassword.src.match("https://icons.veryicon.com/png/o/miscellaneous/hekr/action-hide-password.png")) {
        togglePassword.src ="https://static.thenounproject.com/png/4334035-200.png";
      } else {
        togglePassword.src ="https://icons.veryicon.com/png/o/miscellaneous/hekr/action-hide-password.png";
      }
    }); 
    function validateLoginForm(event) {
      document.querySelectorAll('.error').forEach(function (element) {
        element.textContent = '';
      });
      let email = document.querySelector('input[name="email"]').value;
      if (email === '') {
        document.getElementById('email_error').textContent = 'Please enter an email address';
        return false;
      }
      else if (!/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('email_error').textContent = 'Please enter a valid email address';
        return false;
      }

      let password = document.querySelector('input[name="password"]').value;
      if (password === '') {
        document.getElementById('password_error').textContent = 'Please enter a password';
        return false;
      }
      else if (password.length < 8 || !/\d/.test(password) || !/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
        document.getElementById('password_error').textContent = 'Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter';
        return false;
      }
      return true;
    }
  </script>
</body>
</html>