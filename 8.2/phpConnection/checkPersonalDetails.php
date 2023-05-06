<?php
header("Access-Control-Allow-Origin: *");

$central_db = new PDO('sqlite:vaccines.db');

$name = $_POST["name"];
$surname = $_POST["surname"];
$postcode = $_POST["postcode"];

//Get patient record from central database
$check_patient_record = $central_db->prepare("SELECT NHSNumber FROM patients WHERE Forename = :name AND Surname = :surname AND Postcode = :postcode");
$check_patient_record->bindParam(':name', $name);
$check_patient_record->bindParam(':surname', $surname);
$check_patient_record->bindParam(':postcode', $postcode);
$check_patient_record->execute();
$patient_nhs_number = $check_patient_record->fetchColumn();

//Check if patient record exists in central database
if ($patient_nhs_number) {
    echo json_encode(array('success' => true, 'nhsNumber' => $patient_nhs_number));
} else {
    //If not, then return false
    echo json_encode(array('success' => false));
}
?>
