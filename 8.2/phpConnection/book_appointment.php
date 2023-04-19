<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$patientIdNo = isset($_POST['PatientIdNo']) ? $_POST['PatientIdNo'] : '';
$appointmentDate = isset($_POST['AppointmentDate']) ? $_POST['AppointmentDate'] : '';
$appointmentTime = isset($_POST['AppointmentTime']) ? $_POST['AppointmentTime'] : '';

if (!empty($patientIdNo) && !empty($appointmentDate) && !empty($appointmentTime)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "INSERT INTO GPAppointment (PatientIdNo, AppointmentDate, AppointmentTime) VALUES (:patientIdNo, :appointmentDate, :appointmentTime)";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':patientIdNo', $patientIdNo);
    $stmt->bindParam(':appointmentDate', $appointmentDate);
    $stmt->bindParam(':appointmentTime', $appointmentTime);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Appointment was booked successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to book appointment."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to book appointment. Data is incomplete."));
}
?>
