<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';

if (!empty($NHSNumber)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    // Remove all appointments of the patient
    $queryAppointments = "DELETE FROM GPAppointment WHERE NHSNumber = :NHSNumber";
    $stmtAppointments = $database->prepare($queryAppointments);
    $stmtAppointments->bindParam(':NHSNumber', $NHSNumber);
    $stmtAppointments->execute();

    // Remove the patient details
    $queryPatient = "DELETE FROM LocalPatient WHERE NHSNumber = :NHSNumber";
    $stmtPatient = $database->prepare($queryPatient);
    $stmtPatient->bindParam(':NHSNumber', $NHSNumber);
    
    if ($stmtPatient->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Patient details and appointments have been removed successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to remove patient details and appointments."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to remove patient details and appointments. Data is incomplete."]);
}
?>
