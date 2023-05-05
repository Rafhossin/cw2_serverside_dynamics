<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$NHSNumber = isset($_GET['NHSNumber']) ? $_GET['NHSNumber'] : '';

$response = ["message" => "", "Forename" => ""];

if (!empty($NHSNumber)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    $query = "SELECT Forename FROM LocalPatient WHERE NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':NHSNumber', $NHSNumber);

    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $response["Forename"] = $row['Forename'];
    } else {
        http_response_code(404);
        $response["message"] = "No patient found with the provided NHS Number.";
    }
} else {
    http_response_code(400);
    $response["message"] = "Unable to fetch patient forename. Data is incomplete.";
}

echo json_encode($response);
?>
