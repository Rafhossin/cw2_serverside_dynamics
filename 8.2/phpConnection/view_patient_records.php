
<?php
//w1857209 - Domingo Trimarchi
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//$NHSNumber = '92233359811'; //  NHS Number for testing
$NHSNumber = isset($_GET['NHSNumber']) ? $_GET['NHSNumber'] : '';

$response = ["message" => "", "patients" => []];
//Conditional to check data
if (!empty($NHSNumber)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    $query = "SELECT * FROM LocalPatient WHERE NHSNumber = :NHSNumber";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':NHSNumber', $NHSNumber);
    // Execute the query
    $result = $stmt->execute();
    $patients = [];

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($patients, $row);
    }

    $response["patients"] = $patients;
} else {
    http_response_code(400);
    $response["message"] = "Error data is incomplete.";
}

echo json_encode($response);
?>
