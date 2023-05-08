<?php
//----------------------
////Author: w1822557
//----------------------

header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');

// Select all doctor names
$get_doctor_names = $local_db->prepare("SELECT DoctorFirstName, DoctorId FROM Doctor");
$get_doctor_names->execute();

$doctor_all = array();
while ($doctor = $get_doctor_names->fetch(PDO::FETCH_ASSOC)) {
    $doctor_all[] = array(
        'doctorName' => $doctor['DoctorFirstName'],
        'doctorId' => $doctor['DoctorId']
    );
}

// Return all doctor names
echo json_encode($doctor_all);
?>