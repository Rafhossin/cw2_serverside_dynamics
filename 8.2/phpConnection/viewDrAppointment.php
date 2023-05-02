<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$DoctorId = isset($_POST['DoctorId']) ? $_POST['DoctorId'] : '';
//$NHSNumber = '92233359811'; // Replace this with the actual patient NHS Number

$response = ["message" => "", "appointments" => []];

if (!empty($DoctorId )) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    $query = "SELECT AppointmentNo,AppointmentDate, AppointmentTime,NHSNumber FROM GPAppointment WHERE DoctorId = :drId";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':drId', $DoctorId );

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
