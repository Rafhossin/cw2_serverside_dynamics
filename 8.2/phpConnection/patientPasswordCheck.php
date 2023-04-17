<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$local_db = new PDO('sqlite:GpSurgery.db');

// Get the email and password from the AJAX post request
$email = json_decode(file_get_contents("php://input"), true)["email"];
$password = json_decode(file_get_contents("php://input"), true)["password"];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid email format'));
    exit;
}

// Retrieve patient record from database based on email
$get_patient = $local_db->prepare("SELECT patientIdNo, PatientPassword FROM LocalPatient WHERE PatientEmail = :email");
$get_patient->bindParam(':email', $email);
$get_patient->execute();
$patient_record = $get_patient->fetch(PDO::FETCH_ASSOC);

if (!$patient_record) {
    // Return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Email not found in database'));
    exit;
}

// Check if password matches that of the patient record
if (password_verify($password, $patient_record['PatientPassword'])) {
    // Passwords match, return success message along with patientIdNo as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('success' => 'Login successful', 'patientIdNo' => $patient_record['patientIdNo']));
} else {
    // Passwords don't match, return error message as a JSON object to the login page
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Incorrect password'));
}
?>