<?php
          //Author **** w1785478 ****
// This script allows the user to fetch doctor names by providing their DoctorId in the request.

// Set appropriate headers to allow CORS and specify JSON content-type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Check if 'DoctorId' is provided in the request, if not, set it to an empty string
$DoctorId = isset($_GET['DoctorId']) ? $_GET['DoctorId'] : '';

// Initialize the response array with default values for 'message' and 'fullName'
$response = ["message" => "", "fullName" => ""];

// Check if 'DoctorId' is provided
if (!empty($DoctorId)) {
    // Try to create a new PDO object for connecting to the database
    try {
        $database = new PDO('sqlite:GpSurgery.db');
    } catch (Exception $e) {
        // If there's an error connecting to the database, set HTTP status code to 500 and return an error message
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    // Prepare a SQL query to fetch the doctor's first and last name by their DoctorId
    $query = "SELECT DoctorFirstName, DoctorSurName FROM Doctor WHERE DoctorId = :DoctorId";
    $stmt = $database->prepare($query);

    // Bind the 'DoctorId' parameter to the prepared statement
    $stmt->bindParam(':DoctorId', $DoctorId);

    // Execute the prepared statement
    $stmt->execute();
    // Fetch the result as an associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a result is found, concatenate the first and last name and store it in the 'fullName' key of the response array
    if ($row) {
        $response["fullName"] = $row['DoctorFirstName'] . ' ' . $row['DoctorSurName'];
    } else {
        // If no result is found, set HTTP status code to 404 and return an error message
        http_response_code(404);
        $response["message"] = "Doctor not found.";
    }

} else {
    // If 'DoctorId' is not provided, set HTTP status code to 400 and return an error message
    http_response_code(400);
    $response["message"] = "Unable to fetch doctor name. Data is incomplete.";
}

// Encode the response array as a JSON object and return it
echo json_encode($response);
?>
