<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$central_db = new PDO('sqlite:vaccines.db');
$gpsurgery_db = new PDO('sqlite:GpSurgery.db');

$nhsNumber = isset($_POST["NHSNumber"]) && !empty($_POST["NHSNumber"]) ? $_POST["NHSNumber"] : null;

//$nhsNumber = $_POST["NHSNumber"];

if ($nhsNumber) {
    
// Get patient records from MedicalRecord table in GpSurgery database
$get_patient_records = $gpsurgery_db->prepare("SELECT * FROM MedicalRecord WHERE NHSNumber = :nhsNumber");
$get_patient_records->bindParam(':nhsNumber', $nhsNumber);
$get_patient_records->execute();
$patient_records = $get_patient_records->fetchAll(PDO::FETCH_ASSOC);

if ($patient_records) {
    echo json_encode(array('success' => true, 'patients' => $patient_records, 'message' => ''));
} else {
    // Get patient records from central database
    $get_vaccine_records = $central_db->prepare("SELECT * FROM vaccines WHERE NHSNumber = :nhsNumber");
    $get_vaccine_records->bindParam(':nhsNumber', $nhsNumber);
    $get_vaccine_records->execute();
    $vaccine_records = $get_vaccine_records->fetchAll(PDO::FETCH_ASSOC);

    // Check if the patient exists in the LocalPatient table
    $check_local_patient = $gpsurgery_db->prepare("SELECT * FROM LocalPatient WHERE NHSNumber = :nhsNumber");
    $check_local_patient->bindParam(':nhsNumber', $nhsNumber);
    $check_local_patient->execute();
    $local_patient = $check_local_patient->fetch(PDO::FETCH_ASSOC);

    if ($vaccine_records && $local_patient) {
        echo json_encode(array('success' => true, 'patients' => $vaccine_records, 'message' => ''));
    } elseif ($local_patient) {
        // New patient, please update the patient medical record.
        echo json_encode(array('success' => false, 'message' => 'New patient, please update the patient medical record.'));
    } else {
        // The entered NHS number does not exist in the Gp Surgery.
        echo json_encode(array('success' => false, 'message' => 'The entered NHS number does not exist in the Gp Surgery.'));
    }
}
} else {
    // Return an error message if any of the required input variables are empty or not set
    echo json_encode(array('success' => false, 'message' => 'Invalid or incomplete input data.'));
}
?>
