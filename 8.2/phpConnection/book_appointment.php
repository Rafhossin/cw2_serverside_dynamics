

<?php
//w1857209 - Domingo Trimarchi
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata, true);
//$NHSNumber = '92233359811'; //  NHS Number for testing
$NHSNumber = $request['NHSNumber'];
$AppointmentDate = $request['AppointmentDate'];
$AppointmentTime = $request['AppointmentTime'];
$DoctorId = $request['DoctorId'];

$response = ["message" => ""];
//Conditional to check data
if (!empty($NHSNumber) && !empty($AppointmentDate) && !empty($AppointmentTime) && !empty($DoctorId)) {
    $database = new SQLite3('GpSurgery.db');

    $query = "INSERT INTO GPAppointment (AppointmentDate, AppointmentTime, NHSNumber, DoctorId) VALUES (:AppointmentDate, :AppointmentTime, :NHSNumber, :DoctorId)";
    $stmt = $database->prepare($query);

    $stmt->bindParam(':AppointmentDate', $AppointmentDate);
    $stmt->bindParam(':AppointmentTime', $AppointmentTime);
    $stmt->bindParam(':NHSNumber', $NHSNumber);
    $stmt->bindParam(':DoctorId', $DoctorId);
    // Execute the query
    $result = $stmt->execute();

    if ($result) {
        $response["message"] = "Appointment was booked successfully.";
    } else {
        $response["message"] = "Error: Could not book appointment.";
    }
} else {
    $response["message"] = "Error: incomplete data.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
