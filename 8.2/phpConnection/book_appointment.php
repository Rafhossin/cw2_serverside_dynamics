<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata, true);

$NHSNumber = $request['NHSNumber'];
$AppointmentDate = $request['AppointmentDate'];
$AppointmentTime = $request['AppointmentTime'];
$DoctorId = $request['DoctorId'];

$response = ["message" => ""];

if (!empty($NHSNumber) && !empty($AppointmentDate) && !empty($AppointmentTime) && !empty($DoctorId)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "INSERT INTO GPAppointment (AppointmentDate, AppointmentTime, NHSNumber, DoctorId) VALUES (:AppointmentDate, :AppointmentTime, :NHSNumber, :DoctorId)";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':AppointmentDate', $AppointmentDate);
    $stmt->bindParam(':AppointmentTime', $AppointmentTime);
    $stmt->bindParam(':NHSNumber', $NHSNumber);
    $stmt->bindParam(':DoctorId', $DoctorId);

    $result = $stmt->execute();

    if ($result) {
        $response["message"] = "Appointment was booked successfully.";
    } else {
        $response["message"] = "Error: Could not book appointment.";
    }
} else {
    $response["message"] = "Error: Data is incomplete.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
