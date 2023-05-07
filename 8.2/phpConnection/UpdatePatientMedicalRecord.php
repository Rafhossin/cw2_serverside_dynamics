<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");


$gpsurgery_db = new PDO('sqlite:GpSurgery.db');

$data = json_decode(file_get_contents("php://input"), true);

$nhsNumber = $data["NHSNumber"];
$doctorId = $data["DoctorId"];
$doseNumber = $data["DoseNumber"];
$vaccineManufacturer = $data["VaccineManufacturer"];
$vaccineType = $data["VaccineType"];
$product = $data["Product"];
$vaccineBatchNumber = $data["VaccineBatchNumber"];
$totalSeriesOfDoses = $data["TotalSeriesOfDoses"];
$displayName = $data["DisplayName"];
$snomedCode = $data["SnomedCode"];
$booster = $data["Booster"];
$dateEntered = $data["DateEntered"];



$get_appointment_date = $gpsurgery_db->prepare("SELECT AppointmentDate FROM GPAppointment WHERE NHSNumber = :nhsNumber AND DoctorId = :doctorId ORDER BY AppointmentDate DESC LIMIT 1");
$get_appointment_date->bindParam(':nhsNumber', $nhsNumber);
$get_appointment_date->bindParam(':doctorId', $doctorId);
$get_appointment_date->execute();
$appointment_date = $get_appointment_date->fetch(PDO::FETCH_ASSOC)['AppointmentDate'];


$insert_medical_record = $gpsurgery_db->prepare("INSERT INTO MedicalRecord (NHSNumber, DoseNo, DoctorId, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, Booster) VALUES (:nhsNumber, :doseNo, :doctorId, :vaccinationDate, :vaccineManufacturer, :diseaseTargeted, :vaccineType, :product, :vaccineBatchNumber, :countryOfVaccination, :authority, :site, :totalSeriesOfDoses, :displayName, :snomedCode, :dateEntered, :booster)");

$insert_medical_record->bindParam(':nhsNumber', $nhsNumber);
$insert_medical_record->bindParam(':doseNo', $data["DoseNumber"]);
$insert_medical_record->bindParam(':doctorId', $doctorId);
$insert_medical_record->bindParam(':vaccinationDate', $appointment_date);
$insert_medical_record->bindParam(':vaccineManufacturer', $data["VaccineManufacturer"]);
$insert_medical_record->bindParam(':diseaseTargeted', "COVID-19, 840539006");
$insert_medical_record->bindParam(':vaccineType', $data["VaccineType"]);
$insert_medical_record->bindParam(':product', $data["Product"]);
$insert_medical_record->bindParam(':vaccineBatchNumber', $data["VaccineBatchNumber"]);
$insert_medical_record->bindParam(':countryOfVaccination', "UK");
$insert_medical_record->bindParam(':authority', "Hospital");
$insert_medical_record->bindParam(':site', "Left Arm");
$insert_medical_record->bindParam(':totalSeriesOfDoses', $data["TotalSeriesOfDoses"]);
$insert_medical_record->bindParam(':displayName', $data["DisplayName"]);
$insert_medical_record->bindParam(':snomedCode', $data["SnomedCode"]);
$insert_medical_record->bindParam(':dateEntered', $dateEntered);
$insert_medical_record->bindParam(':booster', $data["Booster"]);

$result = $insert_medical_record->execute();

if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Medical record successfully inserted.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to insert medical record.'));
}
?>
