<?php
   //Author **** w1785478 ****
// This script receives a DoctorId via POST request and fetches the doctor's appointments from the GPAppointment table in the GpSurgery.db SQLite database.

// Set appropriate headers to allow CORS, specify JSON content-type, and allow POST methods
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get the DoctorId from the POST data
$DoctorId = isset($_POST['DoctorId']) ? $_POST['DoctorId'] : '';

// Initialize an array for the response data
$response = ["message" => "", "appointments" => []];

// Check if DoctorId is not empty
if (!empty($DoctorId )) {
    // Try to connect to the SQLite database
    try {
        $db_connection = new SQLite3('GpSurgery.db');
    } catch (Exception $e) {
        // If there's an error connecting to the database, set the appropriate response code and error message
        http_response_code(500);
        $response["message"] = "Unable to connect to the database: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }

    // Prepare a SQL query to fetch appointments for the given DoctorId
    $sqlQuery = "SELECT AppointmentNo,AppointmentDate, AppointmentTime,NHSNumber FROM GPAppointment WHERE DoctorId = :drId";
    $prepared_statement =  $db_connection->prepare($sqlQuery);

    // Bind the DoctorId parameter to the prepared statement
    $prepared_statement->bindParam(':drId', $DoctorId );

    // Execute the prepared statement
    $fectchedResult =  $prepared_statement->execute();
    $doctorAppointments = [];

    // Fetch the appointments and add them to the appointments array
    while ($row =   $fectchedResult ->fetchArray(SQLITE3_ASSOC)) {
        array_push( $doctorAppointments, $row);
    }

    // Add the appointments array to the response data
    $response["appointments"] =  $doctorAppointments;
} else {
    // If DoctorId is empty, set the appropriate response code and error message
    http_response_code(400);
    $response["message"] = "Unable to fetch the doctor appointments. Plese try it again later.";
}

// Output the response data as JSON
echo json_encode($response);
?>
