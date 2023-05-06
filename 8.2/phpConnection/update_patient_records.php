<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$NHSNumber = isset($_POST['NHSNumber']) ? $_POST['NHSNumber'] : '';
$updateField = isset($_POST['updateField']) ? $_POST['updateField'] : '';
$updateValue = isset($_POST['updateValue']) ? $_POST['updateValue'] : '';

$response = ["success" => false, "message" => ""];

if (!empty($NHSNumber) && !empty($updateField) && !empty($updateValue)) {
    try {
        $database = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    if ($updateField === 'Password') {
        $hashed_password = password_hash($updateValue, PASSWORD_DEFAULT);
        $query = "UPDATE LocalPatient SET PatientPassword = :updateValue WHERE NHSNumber = :NHSNumber";
        $updateValue = $hashed_password;
    } else {
        switch ($updateField) {
            case 'Postcode':
                $query = "UPDATE LocalPatient SET Postcode = :updateValue WHERE NHSNumber = :NHSNumber";
                break;
            case 'Email':
                $query = "UPDATE LocalPatient SET PatientEmail = :updateValue WHERE NHSNumber = :NHSNumber";
                break;
            default:
                http_response_code(400);
                $response["message"] = "Invalid update field";
                echo json_encode($response);
                exit();
        }
    }

    $stmt = $database->prepare($query);

    $stmt->bindParam(':NHSNumber', $NHSNumber);
    $stmt->bindParam(':updateValue', $updateValue);

    $result = $stmt->execute();

    if ($result) {
        $response["success"] = true;
        $response["message"] = "Patient records updated successfully";
    } else {
        http_response_code(400);
        $response["message"] = "Unable to update patient records";
    }
} else {
    http_response_code(400);
    $response["message"] = "Unable to update patient records. Data is incomplete.";
}

echo json_encode($response);
?>
