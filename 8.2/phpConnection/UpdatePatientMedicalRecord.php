<?php
          //Author **** w1785478 ****
// This script receives vaccination information via POST request and inserts it into the MedicalRecord table in the GpSurgery.db SQLite database.

// Set appropriate headers to allow CORS, specify JSON content-type, and allow GET and POST methods
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Created a new PDO object for connecting to the database
$gpsurgery_db = new PDO('sqlite:GpSurgery.db');

// Get the POST data
$nhsNumber = $_POST["NHSNumber"];
$doctorId = $_POST["DoctorId"];
$doseNumber = $_POST["DoseNumber"];
$vaccineManufacturer = $_POST["VaccineManufacturer"];
$vaccineType = $_POST["VaccineType"];
$product = $_POST["Product"];
$vaccineBatchNumber = $_POST["VaccineBatchNumber"];
$totalSeriesOfDoses = $_POST["TotalSeriesOfDoses"];
$displayName = $_POST["DisplayName"];
$snomedCode = $_POST["SnomedCode"];
$booster = $_POST["Booster"];
$dateEntered = $_POST["DateEntered"];
$diseaseTargeted = "COVID-19, 840539006";
$countryOfVaccination = "UK";
$authority = "Hospital";
$site = "Left Arm";
$procedureCode = "1324680000000000";

//validate the entered date
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function isDateNotInThePast($date)
{
    $dateToCheck = new DateTime($date);
    $currentDate = new DateTime();

    return $dateToCheck >= $currentDate;
}

if (validateDate($dateEntered)) {
    if (isDateNotInThePast($dateEntered)) {
        // Date is valid and not in the past
    } else {
        // Date is in the past
        echo json_encode(array('success' => false, 'message' => 'Invalid date. The date must not be in the past.'));
        exit();
    }
} else {
    // Date is not valid
    echo json_encode(array('success' => false, 'message' => 'Invalid date.Please select the correct The date, must be in the format YYYY-MM-DD.'));
    exit();
}


// Prepared a SQL query to fetch the latest appointment date for the given NHSNumber and DoctorId
$get_appointment_date = $gpsurgery_db->prepare("SELECT AppointmentDate FROM GPAppointment WHERE NHSNumber = :nhsNumber AND DoctorId = :doctorId ORDER BY AppointmentDate DESC LIMIT 1");
$get_appointment_date->bindParam(':nhsNumber', $nhsNumber);
$get_appointment_date->bindParam(':doctorId', $doctorId);
$get_appointment_date->execute();
$appointment_date = $get_appointment_date->fetch(PDO::FETCH_ASSOC)['AppointmentDate'];

// Check if the appointment date already exists in the MedicalRecord table
$check_existing_record = $gpsurgery_db->prepare("SELECT COUNT(*) FROM MedicalRecord WHERE NHSNumber = :nhsNumber AND DoctorId = :doctorId AND VaccinationDate = :vaccinationDate");
$check_existing_record->bindParam(':nhsNumber', $nhsNumber);
$check_existing_record->bindParam(':doctorId', $doctorId);
$check_existing_record->bindParam(':vaccinationDate', $appointment_date);
$check_existing_record->execute();
$existing_record_count = $check_existing_record->fetchColumn();


// If the appointment date already exists in the MedicalRecord table, return an error message
if ($existing_record_count > 0) {
    echo json_encode(array('success' => false, 'message' => 'Appointment date already exists in the MedicalRecord table.'));
} else {

  // Prepare a SQL query to insert the vaccination data into the MedicalRecord table
$insert_medical_record = $gpsurgery_db->prepare("INSERT INTO MedicalRecord (NHSNumber, DoseNo, DoctorId, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, ProcedureCode, Booster) VALUES (:nhsNumber, :doseNumber, :doctorId, :vaccinationDate, :vaccineManufacturer, :diseaseTargeted, :vaccineType, :product, :vaccineBatchNumber, :countryOfVaccination, :authority, :site, :totalSeriesOfDoses, :displayName, :snomedCode, :dateEntered, :procedureCode, :booster)");

// Bind the parameters to the prepared statement
$insert_medical_record->bindParam(':nhsNumber', $nhsNumber);
$insert_medical_record->bindParam(':doseNumber', $doseNumber);
$insert_medical_record->bindParam(':doctorId', $doctorId);
$insert_medical_record->bindParam(':vaccinationDate', $appointment_date);
$insert_medical_record->bindParam(':vaccineManufacturer', $vaccineManufacturer);
$insert_medical_record->bindParam(':diseaseTargeted', $diseaseTargeted);
$insert_medical_record->bindParam(':vaccineType', $vaccineType);
$insert_medical_record->bindParam(':product', $product);
$insert_medical_record->bindParam(':vaccineBatchNumber', $vaccineBatchNumber);
$insert_medical_record->bindParam(':countryOfVaccination', $countryOfVaccination);
$insert_medical_record->bindParam(':authority', $authority);
$insert_medical_record->bindParam(':site', $site);
$insert_medical_record->bindParam(':totalSeriesOfDoses', $totalSeriesOfDoses);
$insert_medical_record->bindParam(':displayName', $displayName);
$insert_medical_record->bindParam(':snomedCode', $snomedCode);
$insert_medical_record->bindParam(':dateEntered', $dateEntered);
$insert_medical_record->bindParam(':procedureCode', $procedureCode);
$insert_medical_record->bindParam(':booster', $booster);

// Execute the prepared statement and check if it was successful
$result = $insert_medical_record->execute();

if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Medical record successfully inserted.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to insert medical record.'));
}
}
?>
