
<?php
//w1857209 - Domingo Trimarchi
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//$NHSNumber = '92233359811'; //  NHS Number for testing
$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';
//Conditional to validate attributes
if (!empty($NHSNumber)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "No conection to database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    // Remove all appointments of the patient
    $queryAppointments = "DELETE FROM GPAppointment WHERE NHSNumber = :NHSNumber";
    $stmtAppointments = $database->prepare($queryAppointments);
    $stmtAppointments->bindParam(':NHSNumber', $NHSNumber);
        // Execute the query
    $stmtAppointments->execute();

    // Remove the patient details
    $queryPatient = "DELETE FROM LocalPatient WHERE NHSNumber = :NHSNumber";
    $stmtPatient = $database->prepare($queryPatient);
    $stmtPatient->bindParam(':NHSNumber', $NHSNumber);
    //Conditional to catch possible conection or missing data error
    if ($stmtPatient->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Patient details and appointments have been removed successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Error remove patient details."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error data is incomplete."]);
}
?>
