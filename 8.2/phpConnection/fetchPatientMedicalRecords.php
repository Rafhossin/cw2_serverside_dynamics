<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$central_db = new PDO('sqlite:vaccines.db');
$gpsurgery_db = new PDO('sqlite:GpSurgery.db');

$nhsNumber = $_POST["NHSNumber"];
$doctorId = $_POST["DoctorId"];

$get_vaccine_record = $central_db->prepare("SELECT * FROM vaccines WHERE NHSNumber = :nhsNumber");
$get_vaccine_record->bindParam(':nhsNumber', $nhsNumber);
$get_vaccine_record->execute();
$vaccine_record = $get_vaccine_record->fetch(PDO::FETCH_ASSOC);

if ($vaccine_record) {
    $check_existing_record = $gpsurgery_db->prepare("SELECT * FROM MedicalRecord WHERE NHSNumber = :nhsNumber AND DoseNo = :doseNo");
    $check_existing_record->bindParam(':nhsNumber', $vaccine_record['NHSNumber']);
    $check_existing_record->bindParam(':doseNo', $vaccine_record['DoseNo']);
    $check_existing_record->execute();
    $existing_record = $check_existing_record->fetch(PDO::FETCH_ASSOC);

    if (!$existing_record) {
        $insert_medical_record = $gpsurgery_db->prepare("INSERT INTO MedicalRecord (NHSNumber, DoseNo, DoctorId, VaccinationDate, VaccineManufacturer, DiseaseTargeted, VaccineType, Product, VaccineBatchNumber, CountryOfVaccination, Authority, Site, TotalSeriesOfDoses, DisplayName, SnomedCode, DateEntered, ProcedureCode, Booster) VALUES (:nhsNumber, :doseNo, :doctorId, :vaccinationDate, :vaccineManufacturer, :diseaseTargeted, :vaccineType, :product, :vaccineBatchNumber, :countryOfVaccination, :authority, :site, :totalSeriesOfDoses, :displayName, :snomedCode, :dateEntered, :procedureCode, :booster)");

        $insert_medical_record->bindParam(':nhsNumber', $vaccine_record['NHSNumber']);
        $insert_medical_record->bindParam(':doseNo', $vaccine_record['DoseNo']);
        $insert_medical_record->bindParam(':doctorId', $doctorId);
        $insert_medical_record->bindParam(':vaccinationDate', $vaccine_record['VaccinationDate']);
        $insert_medical_record->bindParam(':vaccineManufacturer', $vaccine_record['VaccineManufacturer']);
        $insert_medical_record->bindParam(':diseaseTargeted', $vaccine_record['DiseaseTargeted']);
        $insert_medical_record->bindParam(':vaccineType', $vaccine_record['VaccineType']);
        $insert_medical_record->bindParam(':product', $vaccine_record['Product']);
        $insert_medical_record->bindParam(':vaccineBatchNumber', $vaccine_record['VaccineBatchNumber']);
        $insert_medical_record->bindParam(':countryOfVaccination', $vaccine_record['CountryOfVaccination']);
        $insert_medical_record->bindParam(':authority', $vaccine_record['Authority']);
        $insert_medical_record->bindParam(':site', $vaccine_record['Site']);
        $insert_medical_record->bindParam(':totalSeriesOfDoses', $vaccine_record['TotalSeriesOfDoses']);
        $insert_medical_record->bindParam(':displayName', $vaccine_record['DisplayName']);
        $insert_medical_record->bindParam(':snomedCode', $vaccine_record['SnomedCode']);
        $insert_medical_record->bindParam(':dateEntered', $vaccine_record['DateEntered']);
        $insert_medical_record->bindParam(':procedureCode', $vaccine_record['ProcedureCode']);
        $insert_medical_record->bindParam(':booster', $vaccine_record['Booster']);

        $insert_medical_record->execute();
    }

    $get_patient_records = $gpsurgery_db->prepare("SELECT * FROM MedicalRecord WHERE NHSNumber = :nhsNumber AND DoctorId = :doctorId");
    $get_patient_records->bindParam(':nhsNumber', $nhsNumber);
    $get_patient_records->bindParam(':doctorId', $doctorId);
    $get_patient_records->execute();
    $patient_records = $get_patient_records->fetchAll(PDO::FETCH_ASSOC);

    if ($patient_records) {
        echo json_encode(array('success' => true, 'patients' => $patient_records));
    } else {
        echo json_encode(array('success' => false, 'message' => 'New patient, please update the patient medical record'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'NHS number you entered, does not exist'));
}
?>

