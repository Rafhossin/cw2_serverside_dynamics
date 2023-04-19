<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$bookingId = isset($_POST['BookingId']) ? $_POST['BookingId'] : '';

if (!empty($bookingId)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "DELETE FROM GPAppointment WHERE BookingId = :bookingId";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':bookingId', $bookingId);

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
