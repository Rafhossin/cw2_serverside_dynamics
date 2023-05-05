<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$central_db = new PDO('sqlite:vaccines.db');

// Get the NHS number from the AJAX POST request
$nhs_number = $_POST['nhsNumber'];
// Get patient email and password from POST request
$patient_email = $_POST['email'];
$patient_password = $_POST['password'];
// Hash the password
$hashed_password = password_hash($patient_password, PASSWORD_DEFAULT);

// Get patient record from central database
$check_nhs_number = $central_db->prepare('SELECT * FROM patients WHERE NHSNumber = :nhs_number');
$check_nhs_number->bindParam(':nhs_number', $nhs_number);
$check_nhs_number->execute();
$patient_record = $check_nhs_number->fetch(PDO::FETCH_ASSOC);

// Check if patient record exists in central database
if ($patient_record) {

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
    $insert_local_patient->bindParam(':patient_password', $hashed_password);
    $insert_local_patient->execute();


    // // Fetch the required columns from the vaccine table in the central database
    // $query = "SELECT NHSNumber, DoseNo, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, ProcedureCode, Booster FROM vaccine";
    // $statement = $central_db->query($query);

    // // Insert the fetched rows into the LocalPatientVaccination table in the local database
    // $insert_query = "INSERT INTO LocalPatientVaccination (NHSNumber, DoseNo, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, ProcedureCode, Booster) VALUES (:nhs_number, :dose_no, :vaccination_date, :vaccine_manufacturer, :disease_targeted, :vaccine_type, :product, :vaccine_batch_number, :country_of_vaccination, :authority, :site, :total_series_of_doses, :display_name, :snomed_code, :date_entered, :procedure_code, :booster)";
    // $insert_statement = $local_db->prepare($insert_query);

    // while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    //     $insert_statement->execute($row);
    // }

    echo json_encode(array('success' => true));
}
?>
