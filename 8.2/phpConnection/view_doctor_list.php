<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');

// Select all doctor names
$get_doctor_names = $local_db->prepare("SELECT DoctorFirstName FROM Doctor");
$get_doctor_names->execute();

$doctor_names = array();
while ($doctor = $get_doctor_names->fetch(PDO::FETCH_ASSOC)) {
    $doctor_names[] = $doctor['DoctorFirstName'];
}

// Return all doctor names
echo json_encode($doctor_names);
?>




