
<?php
//w1857209 - Domingo Trimarchi
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//$NHSNumber = '92233359811'; //  NHS Number for testing
$appointmentNo = isset($_POST['AppointmentNo']) ? $_POST['AppointmentNo'] : '';
$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';
//Conditional to check data
if (!empty($appointmentNo) && !empty($NHSNumber)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "DELETE FROM GPAppointment WHERE AppointmentNo = :appointmentNo AND NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':appointmentNo', $appointmentNo);
    $stmt->bindParam(':NHSNumber', $NHSNumber);
//Conditional to catch possible conection or missing data error
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Appointment was canceled successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Error to cancel appointment."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error data is incomplete."]);
}
?>
