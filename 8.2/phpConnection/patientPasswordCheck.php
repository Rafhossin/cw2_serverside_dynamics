<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];

// Check if email exists in database
$check_email = $local_db->prepare("SELECT * FROM LocalPatient WHERE PatientEmail = :email");
$check_email->bindParam(':email', $email);
$check_email->execute();

$patient_record = $check_email->fetch(PDO::FETCH_ASSOC);

if ($patient_record) {
    // Verify password
    if (password_verify($password, $patient_record['PatientPassword'])) {
        // Return true with the NHS number
        echo json_encode(array('success' => true, 'NHSNumber' => $patient_record['NHSNumber']));
    } else {
        // Return false
        echo json_encode(array('success' => false));
    }
} else {
    // Return false
    echo json_encode(array('success' => false));
}

?>