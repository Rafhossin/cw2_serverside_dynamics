<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$central_db = new PDO('sqlite:vaccines.db');

// Get the name, surname, and postcode from the AJAX post request
$name = $_POST["name"];
$surname = $_POST["surname"];
$postcode = $_POST["postcode"];

// Get patient record from central database
$check_patient_record = $central_db->prepare("SELECT patientNHSNumber FROM patients WHERE Forename = :name AND Surname = :surname AND Postcode = :postcode");
$check_patient_record->bindParam(':name', $name);
$check_patient_record->bindParam(':surname', $surname);
$check_patient_record->bindParam(':postcode', $postcode);
$check_patient_record->execute();
$patient_nhs_number = $check_patient_record->fetchColumn();

// Check if patient record exists in central database
if ($patient_nhs_number) {
    // Return patient NHS number as a JSON object to the registration page
    header('Content-Type: application/json');
    echo json_encode(array('NHSNumber' => $patient_nhs_number));
} else {
    // Patient record does not exist in central database
    echo "Patient record does not exist in central database";
} 
?>
