<?php session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login_page.php');
}
?>

<?php
include 'connection.php';


if (isset($_POST['cancel'])) {
    header("Location: index.php");
}
if (isset($_GET['log_out'])) {
    session_unset();
    session_destroy();
    header("Location: login_page.php");
}

$name = $company = $title = $email = $phone = $work_hours = $info = $gender = $password = $password = '';
$nameErr = $companyErr = $titleErr = $emailErr = $phoneErr = $work_hoursErr = $infoErr = $genderErr = $passwordErr = $cpasswordErr = '';



function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = true;


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
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s.'-]+$/", $company)) {
            $companyErr = "Only letters, numbers, spaces, periods, apostrophes, and hyphens are allowed.";
        }
    }

    $title = test_input($_POST['title']);
    if (empty($title)) {
        $titleErr = "Please enter your title";
        $valid = false;
    } else {
        if (!preg_match("/^[a-zA-Z\s.'-]+$/", $title)) {
            $titleErr = "Only letters, spaces, periods, apostrophes, and hyphens are allowed.";
        }
    }

    $email = test_input($_POST['email']);
    if (empty($email)) {
        $emailErr = "Please enter your email address";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Please enter a valid email address";
        $valid = false;
    } else {
        $stmt = $conn->prepare("SELECT id FROM employee_info WHERE email = ? and is_deleted is null");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $emailErr = "Email address already exists";
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
    }else{
        $stmt = $conn->prepare("select * from employee_info where phone = ? and is_deleted is null");
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $phoneErr = "Phone number already exists";
            $valid = false;
        }
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

    if (!empty($_POST["password"]) && !empty($_POST["cpassword"])) {
        $password = test_input($_POST["password"]);
        $cpassword = test_input($_POST["cpassword"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter";
            $valid = false;
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $passwordErr = "Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter";
            $valid = false;
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $passwordErr = "Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter";
            $valid = false;
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $passwordErr = "Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter";
            $valid = false;
        } elseif ($password !== $cpassword) {
            $cpasswordErr = "Passwords do not match!";
            $valid = false;
        }
    } else {
        $passwordErr = "Please enter a password";
        $cpasswordErr = "Please confirm your password";
        $valid = false;
    }

    $info = test_input($_POST['info']);


    if ($valid) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO employee_info (name, company, title, gender, email, phone, working_hours, additional_info, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssisss",
            $name,
            $company,
            $title,
            $gender,
            $email,
            $phone,
            $work_hours,
            $info,
            $hashed_password
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Manrope:wght@200..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>

    <style>
        
    </style>
</head>

<body class="bg-light">

    <div class="wrapper vh-100">
        <header class="">
            <nav class="navbar navbar-expand-lg navbar-light bg-dark" style="">
                <a class="navbar-brand text-light" href="index.php">ARCS Infotech</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active text-light">
                            <a class="nav-link text-light" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="#">Pricing</a>
                        </li>
                        <li class="nav-item dropdown ">
                            <a class="nav-link  text-light" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Services
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-light" href="#">Action</a>
                                <a class="dropdown-item text-light" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-light" href="#">Something else here</a>
                            </div>
                        </li>
                        <li class="nav-item text-light">
                            <a class="nav-link disabled text-light" href="#" tabindex="-1"
                                aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
                <form action="" method="get" class="d-flex">

                    <button class="btn btn-primary" name="log_out">Log out</button>
                </form>
            </nav>
        </header>


        <div class="form_container d-flex">

            <div class="d-flex justify-content-center align-items-center p-5 signup_form_wrapper">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
                    class="d-flex p-4 flex-column gap-4 bg-white border border-light-subtle rounded-3 shadow-sm signup_form"
                    onsubmit="return validateForm(event)">
                    <div class="text-center mt-3">
                        <img src="logo.png" alt="">
                    </div>
                    <div><span class="text-danger req_field_msg" style="">*Required field</span></div>
                    <div class="fields_container">
                        <div class="signup">
                            <div class="form_fields">
                                <label for="name" class="form-label">Client Name : <span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="name"
                                    value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter your name">
                                <span class="error"><?php echo !empty($nameErr) ? $nameErr : ''; ?></span>
                                <span id="nameErr" class="error"></span>
                            </div>
                            <div class="form_fields">
                                <label for="company" class="form-label">Company :<span class="text-danger"> *</span>
                                </label>
                                <input type="text" class="form-control" name="company"
                                    value="<?php echo htmlspecialchars($company); ?>"
                                    placeholder="Enter your company name">
                                <span class="error"><?php echo $companyErr; ?></span>
                                <span id="companyErr" class="error"></span>
                            </div>
                            <div class="form_fields">
                                <label for="title" class="form-label">Title : <span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="title"
                                    value="<?php echo htmlspecialchars($title); ?>"
                                    placeholder="Positon at your company">
                                <span class="error"><?php echo $titleErr; ?></span>
                                <span id="titleErr" class="error"></span>
                            </div>
                            <div class="form_fields">
                                <label for="work_hours" class="form-label">Working Hours : <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="work_hours"
                                    value="<?php echo htmlspecialchars($work_hours); ?>" placeholder="Working hours">
                                <span class="error"><?php echo $work_hoursErr; ?></span>
                                <span id="work_hoursErr" class="error"></span>
                            </div>

                            <div class="form_fields">
                                <label for="phone" class="form-label">Phone : <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?php echo htmlspecialchars($phone); ?>"
                                    placeholder="Enter your phone number">
                                <span class="error"><?php echo $phoneErr; ?></span>
                                <span id="phoneErr" class="error"></span>
                            </div>
                            <div class="form_fields">
                                <label for="email" class="form-label">Email : <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="email"
                                    value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your email">
                                <span class="error"><?php echo $emailErr; ?></span>
                                <span id="emailErr" class="error"></span>
                            </div>
                        </div>
                        <div class="signup">
                            
                            <div class="form_fields">
                                <label for="password" class="form-label">Password : <span
                                        class="text-danger">*</span></label>
                                <div class="eye-container position-relative">
                                    <input type="password" class="form-control" name="password"
                                        value="<?php echo htmlspecialchars($password); ?>" id="password"
                                        placeholder="Enter password">
                                    <img src="eye-open.png" alt="" id="eye" class="position-absolute eye_icon"
                                        style="">
                                </div>
                                <span class="error" class="form-label"><?php echo $passwordErr; ?></span>
                                <span id="passwordErr" class="error"></span>
                            </div>
                            <div class="form_fields position-relative">
                                <label for="cpassword" class="form-label" >Confirm Password : <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control position-relative" name="cpassword"
                                    placeholder="Confirm password" id="confirm_password">
                                    <img src="eye-open.png" alt="" id="con_eye" class="position-absolute eye_icon"
                                        style="top:43px">
                                <span class="error"><?php echo $cpasswordErr; ?></span>
                                <span id="cpasswordErr" class="error"></span>
                            </div>

                            <div class="gender_field">
                                <label for="gender" class="form-label gender_label">Gender : <span
                                        class="text-danger">*</span></label>
                                <div class="gender_input flex-row align-items-center" style="">
                                    <input type="radio" class="form-check-input" id="male" name="gender" <?php if (isset($gender) && $gender == "male")
                                        echo "checked"; ?> value="male">
                                    <label for="male">Male</label>
                                    <input type="radio" class="form-check-input" id="female" name="gender" <?php if (isset($gender) && $gender == "female")
                                        echo "checked"; ?> value="female">
                                    <label for="female">Female</label>
                                    <input type="radio" class="form-check-input" id="others" name="gender" <?php if (isset($gender) && $gender == "others")
                                        echo "checked"; ?> value="others">
                                    <label for="others">Others</label>
                                    <span class="error"><?php echo $genderErr; ?></span>
                                    <span id="genderErr" class="error"></span>
                                </div>
                            </div>

                            
                            
                        </div>
                    </div>
                    <div class="add_info">
                                <label for="info" class="form-label">Additional Information : </label>
                                <textarea id="" class="form-control textarea" name="info" rows="4" cols="58" name="info"
                                    value="<?php echo htmlspecialchars($info); ?>"
                                    placeholder="Enter additonal info here...."></textarea>
                    </div>
                    <div class="submit text-center d-flex justify-content-end gap-3">
                        <button name="cancel" type="button" id="cancelbtn"
                            class="btn btn-secondary btn-outline-secondary text-light btn-md">Cancel</button>
                        <button type="submit" name="submit"
                            class="btn btn-primary btn-outline-primary text-light btn-md">Submit</button>
                    </div>

                </form>
            </div>
        </div>

        <footer class="bg-dark text-white text-center py-3 " style="">
            <p>&copy; 2025 ARCS Infotech. All rights reserved.</p>
        </footer>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

    <script>

        let cancelbtn = document.querySelector("#cancelbtn");
        console.log("hello-----------");
        cancelbtn.addEventListener('click', () => {
            window.location.href = 'index.php';
        });


        let eye = document.querySelector("#eye");
        let con_eye = document.querySelector("#con_eye");
        let password = document.querySelector("#password");
        let confirm_password = document.querySelector("#confirm_password");


        eye.addEventListener('click', function () {
            if (password.type == 'password') {
                password.type = 'text';
                eye.src = 'eye-close.png';
            } else {
                password.type = 'password';
                eye.src = 'eye-open.png';
            }
        });
        con_eye.addEventListener('click', function () {
            if (confirm_password.type == 'password') {
                confirm_password.type = 'text';
                con_eye.src = 'eye-close.png';
            } else {
                confirm_password.type = 'password';
                con_eye.src = 'eye-open.png';
            }
        });

        function validateForm(event) {
            // console.log("Hello!!!");
            document.querySelectorAll('.error').forEach(function (element) {
                element.textContent = '';
            });

            // event.preventDefault();

            let isValid = true;
            let name = document.querySelector('input[name="name"]').value;
            if (name === '') {
                document.getElementById('nameErr').textContent = 'Please enter your name';
                isValid = false;
            }
            else if (!/^[a-zA-Z-' ]*$/.test(name)) {
                document.getElementById('nameErr').textContent = 'Only letters and white space allowed';
                isValid = false;
            }

            let company = document.querySelector('input[name="company"]').value;
            if (company === '') {
                document.getElementById('companyErr').textContent = 'Please enter your company name';
                isValid = false;
            }
            else if (!/^[a-zA-Z-' ]*$/.test(company)) {
                document.getElementById('companyErr').textContent = 'Only letters and white space allowed';
                isValid = false;
            }

            let title = document.querySelector('input[name="title"]').value;
            if (title === '') {
                document.getElementById('titleErr').textContent = 'Please enter your title';
                isValid = false;
            }
            else if (!/^[a-zA-Z-' ]*$/.test(title)) {
                document.getElementById('titleErr').textContent = 'Only letters and white space allowed';
                isValid = false;
            }

            let email = document.querySelector('input[name="email"]').value;
            if (email === '') {
                document.getElementById('emailErr').textContent = 'Please enter an email address';
                isValid = false;
            }
            else if (!/\S+@\S+\.\S+/.test(email)) {
                document.getElementById('emailErr').textContent = 'Please enter a valid email address';
                isValid = false;
            }

            let phone = document.querySelector('input[name="phone"]').value;
            if (phone === '') {
                document.getElementById('phoneErr').textContent = 'Please enter a phone number';
                isValid = false;
            }
            else if (!/^[0-9]{10}$/.test(phone)) {
                document.getElementById('phoneErr').textContent = 'Please enter a valid phone number';
                isValid = false;
            }

            let gender = document.querySelector('input[name="gender"]:checked');
            if (!gender) {
                document.getElementById('genderErr').textContent = 'Please select a gender';
                isValid = false;
            }

            let password = document.querySelector('input[name="password"]').value;
            let cpassword = document.querySelector('input[name="cpassword"]').value;
            if (password === '') {
                document.getElementById('passwordErr').textContent = 'Please enter a password';
                isValid = false;
            }
            else if (password.length < 8 || !/\d/.test(password) || !/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
                document.getElementById('passwordErr').textContent = 'Password must be at least 8 characters, contain a number, a capital letter, and a lowercase letter';
                isValid = false;
            }
            if (cpassword === '') {
                document.getElementById('cpasswordErr').textContent = 'Please confirm your password';
                isValid = false;
            }
            else if (password !== cpassword) {
                document.getElementById('cpasswordErr').textContent = 'Passwords do not match';
                isValid = false;
            }

            let work_hours = document.querySelector('input[name="work_hours"]').value;
            if (work_hours === '') {
                document.getElementById('work_hoursErr').textContent = 'Please enter your working hours';
                isValid = false;
            } else if (!/^\d+(\.\d{1,2})?$/.test(work_hours)) {
                document.getElementById('work_hoursErr').textContent = 'Please enter valid working hours';
                isValid = false;
            }

            return isValid;

        }
    </script>

</body>

</html>