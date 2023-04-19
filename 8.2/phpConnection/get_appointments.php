<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$patientIdNo = isset($_POST['PatientIdNo']) ? $_POST['PatientIdNo'] : '';

$response = ["message" => "", "appointments" => []];

if (!empty($patientIdNo)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "SELECT AppointmentDate, AppointmentTime FROM GPAppointment WHERE PatientIdNo = :patientIdNo";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':patientIdNo', $patientIdNo);

    $result = $stmt->execute();
    $appointments = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($appointments, $row);
    }

    $response["appointments"] = $appointments;
} else {
    http_response_code(400);
    $response["message"] = "Unable to fetch appointments. Data is incomplete.";
}

echo json_encode($response);
?>
