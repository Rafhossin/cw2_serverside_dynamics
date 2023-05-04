<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$appointmentNo = isset($_POST['AppointmentNo']) ? $_POST['AppointmentNo'] : '';
$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';

if (!empty($appointmentNo) && !empty($NHSNumber)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "DELETE FROM GPAppointment WHERE AppointmentNo = :appointmentNo AND NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':appointmentNo', $appointmentNo);
    $stmt->bindParam(':NHSNumber', $NHSNumber);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Appointment was canceled successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to cancel appointment."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to cancel appointment. Data is incomplete."]);
}
?>
