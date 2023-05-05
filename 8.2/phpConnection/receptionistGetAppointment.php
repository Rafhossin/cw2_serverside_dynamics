<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');

$doctorName = $_POST['DoctorName'];

// Retrieve appointments for the specified doctor
$retrieve_appointments = $local_db->prepare("SELECT * FROM Appointments WHERE DoctorName = :doctorName");
$retrieve_appointments->bindParam(':doctorName', $doctorName);
$retrieve_appointments->execute();

$appointments = $retrieve_appointments->fetchAll(PDO::FETCH_ASSOC);

// Return the appointments as JSON response
echo json_encode($appointments);
?>
