<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$central_db = new PDO('sqlite:vaccines.db');

// Get the NHS number from the AJAX POST request
$nhs_number = $_POST['nhsNumber'];

// Get patient record from central database
$check_nhs_number = $central_db->prepare('SELECT * FROM patients WHERE NHSNumber = :nhs_number');
$check_nhs_number->bindParam(':nhs_number', $nhs_number);
$check_nhs_number->execute();
$patient_record = $check_nhs_number->fetch(PDO::FETCH_ASSOC);

// Check if patient record exists in central database
if ($patient_record) {
    // Get patient email and password from POST request
    $patient_email = $_POST['email'];
    $patient_password = $_POST['password'];

    // Connect to local database
    $local_db = new PDO('sqlite:GpSurgery.db');

    // Insert patient record into local database
    $insert_local_patient = $local_db->prepare('INSERT INTO LocalPatient (NHSNumber, Forename, Surname, PersonDOB, GenderCode, Postcode, PatientEmail, PatientPassword) VALUES (:nhs_number, :forename, :surname, :persondob, :gendercode, :postcode, :patient_email, :patient_password)');
    $insert_local_patient->bindParam(':nhs_number', $patient_record['NHSNumber']);
    $insert_local_patient->bindParam(':forename', $patient_record['Forename']);
    $insert_local_patient->bindParam(':surname', $patient_record['Surname']);
    $insert_local_patient->bindParam(':persondob', $patient_record['PersonDOB']);
    $insert_local_patient->bindParam(':gendercode', $patient_record['GenderCode']);
    $insert_local_patient->bindParam(':postcode', $patient_record['Postcode']);
    $insert_local_patient->bindParam(':patient_email', $patient_email);
    $insert_local_patient->bindParam(':patient_password', $patient_password);
    $insert_local_patient->execute();

    // Return the patient information as a JSON object
    $patient_info = array(
        'nhsNumber' => $patient_record['NHSNumber'],
        'forename' => $patient_record['Forename'],
        'surname' => $patient_record['Surname'],
        'personDOB' => $patient_record['PersonDOB'],
        'genderCode' => $patient_record['GenderCode'],
        'postcode' => $patient_record['Postcode'],
        'patientEmail' => $patient_email,
        'patientPassword' => $patient_password
    );

    echo json_encode($patient_info);
} else {
    echo "NHSNumber does not exist in central database";
}
?>
