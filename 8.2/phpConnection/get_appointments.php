<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Read the input data and decode the JSON
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);

// Get the NHSNumber from the input data
$NHSNumber = isset($inputData['NHSNumber']) ? $inputData['NHSNumber'] : null;

$response = ["message" => "", "appointments" => []];

if (!empty($NHSNumber)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    $query = "SELECT AppointmentDate, AppointmentTime FROM GPAppointment WHERE NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);

    $stmt->bindValue(':NHSNumber', $NHSNumber, SQLITE3_TEXT);

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
