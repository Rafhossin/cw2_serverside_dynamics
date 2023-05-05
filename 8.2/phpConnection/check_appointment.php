<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->NHSNumber)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "SELECT * FROM Appointments WHERE NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);
    $stmt->bindValue(':NHSNumber', $data->NHSNumber, SQLITE3_TEXT);

    $result = $stmt->execute();
    $appointment = $result->fetchArray(SQLITE3_ASSOC);

    if ($appointment) {
        http_response_code(200);
        echo json_encode(["message" => "Appointment found.", "data" => $appointment]);
    } else {
        http_response_code(200);
        echo json_encode(["message" => "No existing appointment."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to check appointment. Data is incomplete."]);
}
?>
