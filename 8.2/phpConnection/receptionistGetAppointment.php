<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');

$doctorId = $_POST['doctorId'];

// Retrieve appointments for the specified doctor
$retrieve_appointments = $local_db->prepare("SELECT * FROM GPAppointment WHERE DoctorId = :doctorId");
$retrieve_appointments->bindParam(':doctorId', $doctorId);
$retrieve_appointments->execute();

$appointments = $retrieve_appointments->fetchAll(PDO::FETCH_ASSOC);

// Return the appointments as JSON response
echo json_encode($appointments);
?>