<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$local_db = new PDO('sqlite:GpSurgery.db');

// Get the email from the AJAX post request
$email = json_decode(file_get_contents("php://input"), true)["email"];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Return error message as a JSON object to the registration page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid email format'));
    exit;
}

// Check if email is in exists in doctor table
$check_doctor_email = $local_db->prepare("SELECT COUNT(*) FROM Doctor WHERE DoctorEmail = :email");
$check_doctor_email->bindParam(':email', $email);
$check_doctor_email->execute();
$doctor_email_count = $check_doctor_email->fetchColumn();

if ($doctor_email_count > 0) {
    // Return true as a JSON object to the registration page with doctor flag and email
    header('Content-Type: application/json');
    echo json_encode(array('exists' => true, 'user_type' => 'doctor', 'email' => $email));
    exit;
}

// Check if email is in receptionist table
$check_receptionist_email = $local_db->prepare("SELECT COUNT(*) FROM Receptionist WHERE ReceptionistEmail = :email");
$check_receptionist_email->bindParam(':email', $email);
$check_receptionist_email->execute();
$receptionist_email_count = $check_receptionist_email->fetchColumn();

if ($receptionist_email_count > 0) {
    // Return true as a JSON object to the registration page with receptionist flag and email
    header('Content-Type: application/json');
    echo json_encode(array('exists' => true, 'user_type' => 'receptionist', 'email' => $email));
    exit;
}

// Return false as a JSON object to the registration page if email not found in either table
header('Content-Type: application/json');
echo json_encode(array('exists' => false));
?>