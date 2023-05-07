<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$gpsurgery_db = new PDO('sqlite:GpSurgery.db');

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

$get_appointment_date = $gpsurgery_db->prepare("SELECT AppointmentDate FROM GPAppointment WHERE NHSNumber = :nhsNumber AND DoctorId = :doctorId ORDER BY AppointmentDate DESC LIMIT 1");
$get_appointment_date->bindParam(':nhsNumber', $nhsNumber);
$get_appointment_date->bindParam(':doctorId', $doctorId);
$get_appointment_date->execute();
$appointment_date = $get_appointment_date->fetch(PDO::FETCH_ASSOC)['AppointmentDate'];

$insert_medical_record = $gpsurgery_db->prepare("INSERT INTO MedicalRecord (NHSNumber, DoseNo, DoctorId, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, ProcedureCode, Booster) VALUES (:nhsNumber, :doseNumber, :doctorId, :vaccinationDate, :vaccineManufacturer, :diseaseTargeted, :vaccineType, :product, :vaccineBatchNumber, :countryOfVaccination, :authority, :site, :totalSeriesOfDoses, :displayName, :snomedCode, :dateEntered, :procedureCode, :booster)");

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

$result = $insert_medical_record->execute();

if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Medical record successfully inserted.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to insert medical record.'));
}
?>
