<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';

if (!empty($NHSNumber)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "SELECT AppointmentDate, AppointmentTime, AppointmentNo FROM GPAppointment WHERE NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);
    $stmt->bindValue(':NHSNumber', $NHSNumber, SQLITE3_TEXT);

    $result = $stmt->execute();
    $appointments = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $appointments[] = $row;
    }

    http_response_code(200);
    echo json_encode(["appointments" => $appointments]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to fetch appointments. Data is incomplete."]);
}
?>
