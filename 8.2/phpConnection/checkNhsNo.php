
<?php
header("Access-Control-Allow-Origin: *");
// Connect to central database
$central_db = new PDO('sqlite:vaccines.db');

// Get the nhs number from the AJAX post request
$nhs_number = $_POST['nhsNumber'];

// Get patient record from central database
$check_nhs_number = $central_db->prepare('SELECT * FROM patients WHERE NHSNumber = :nhs_number');
$check_nhs_number->bindParam(':nhs_number', $nhs_number);
$check_nhs_number->execute();
$patient_record = $check_nhs_number->fetch(PDO::FETCH_ASSOC);

// Check if patient record exists in central database
if ($patient_record) {
    // Return patient record as a JSON object to the registration page
    header('Content-Type: application/json');
    echo json_encode($patient_record);
} else {
    // NHSNumber does not exist in central database
    echo "NHSNumber does not exist in central database";
} 

?>