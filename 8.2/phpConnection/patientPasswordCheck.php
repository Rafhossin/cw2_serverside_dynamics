<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];

// Check if email and password match in database
$check_email_password = $local_db->prepare("SELECT NHSNumber FROM LocalPatient WHERE PatientEmail = :email AND PatientPassword = :password");
$check_email_password->bindParam(':email', $email);
$check_email_password->bindParam(':password', $password);
$check_email_password->execute();

$nhs_number_result = $check_email_password->fetchColumn();

if ($nhs_number_result) {
    // Return true with the NHS number
    echo json_encode(array('success' => true, 'nhsNumber' => $nhs_number_result));
} else {
    // Return false
    echo json_encode(array('success' => false));
}
?>