<?php
header("Access-Control-Allow-Origin: *");

// Connect to the database
$db = new PDO('sqlite:GpSurgery.db');

// Get the email and password from the AJAX post request
$email = json_decode(file_get_contents("php://input"), true)["email"];
$password = json_decode(file_get_contents("php://input"), true)["password"];
$user_type = json_decode(file_get_contents("php://input"), true)["user_type"];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid email format'));
    exit;
}

// Retrieve user record from database based on email and user type
if ($user_type == "doctor") {
    $get_user = $db->prepare("SELECT doctorId, DoctorPassword FROM Doctor WHERE DoctorEmail = :email");
} elseif ($user_type == "receptionist") {
    $get_user = $db->prepare("SELECT receptionistId, ReceptionistPassword FROM Receptionist WHERE ReceptionistEmail = :email");
} else {
    // Return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid user type'));
    exit;
}

$get_user->bindParam(':email', $email);
$get_user->execute();
$user_record = $get_user->fetch(PDO::FETCH_ASSOC);

if (!$user_record) {
    // Return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Email not found in database'));
    exit;
}

// Check if password matches that of the user record
if (password_verify($password, $user_record['DoctorPassword']) || password_verify($password, $user_record['ReceptionistPassword'])) {
    // Passwords match, return success message along with doctorId or receptionistId as a JSON object to the login page
    header('Content-Type: application/json');
    if ($user_type == "doctor") {
        echo json_encode(array('success' => 'Login successful', 'doctorId' => $user_record['doctorId']));
    } else {
        echo json_encode(array('success' => 'Login successful', 'receptionistId' => $user_record['receptionistId']));
    }
} else {
    // Passwords don't match, return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Incorrect password'));
}
?>