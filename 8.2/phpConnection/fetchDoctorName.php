<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$DoctorId = isset($_GET['DoctorId']) ? $_GET['DoctorId'] : '';

$response = ["message" => "", "fullName" => ""];

if (!empty($DoctorId)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    $query = "SELECT DoctorFirstName, DoctorSurName FROM Doctor WHERE DoctorId = :DoctorId";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':DoctorId', $DoctorId);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $response["fullName"] = $row['DoctorFirstName'] . ' ' . $row['DoctorSurName'];
    } else {
        http_response_code(404);
        $response["message"] = "Doctor not found.";
    }

} else {
    http_response_code(400);
    $response["message"] = "Unable to fetch doctor name. Data is incomplete.";
}

echo json_encode($response);
?>
