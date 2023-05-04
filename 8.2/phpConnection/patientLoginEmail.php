<?php
header('Access-Control-Allow-Origin: *');
$local_db = new PDO('sqlite:GpSurgery.db');
$email = $_POST['emailAddress'];

// Check if email is in database
$check_email = $local_db->prepare("SELECT PatientEmail FROM LocalPatient WHERE PatientEmail = :email");
$check_email->bindParam(':email', $email);
$check_email->execute();

$email_result = $check_email->fetchColumn();

if ($email_result) {
    // Return true if email found
    echo json_encode(true);
} else {
    // Return false if email not found
    echo json_encode(false);
}
?>