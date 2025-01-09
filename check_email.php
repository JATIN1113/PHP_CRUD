<?php  
include('connection.php');

header('Content-Type: application/json');

// Validate POST inputs
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';

if (empty($email) || empty($phone)) {
    echo json_encode(array('error' => 'Email or phone is missing.'));
    exit;
}

// Check if email exists
$sql = "SELECT * FROM employee_info WHERE email = ?";
$emailQuery = $conn->prepare($sql);
$emailQuery->bind_param('s', $email);
$emailQuery->execute();
$emailResult = $emailQuery->get_result();
$emailExist = $emailResult->num_rows > 0;

// Check if phone exists
$phoneQuery = "SELECT * FROM employee_info WHERE phone = ?";
$phonestmt = $conn->prepare($phoneQuery);
$phonestmt->bind_param('s', $phone);
$phonestmt->execute();
$phoneResult = $phonestmt->get_result();
$phoneExist = $phoneResult->num_rows > 0;

// Response
$response = array(
    'emailExist' => $emailExist,
    'phoneExist' => $phoneExist
);

echo json_encode($response);
?>