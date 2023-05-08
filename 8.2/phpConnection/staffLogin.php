<?php
//----------------------
////Author: w1820984
//----------------------

header("Access-Control-Allow-Origin: *");

$local_db = new PDO('sqlite:GpSurgery.db');
$email = $_POST['emailAddress'];

//Check if email is in doctor records
$check_doctor_email = $local_db->prepare("SELECT DoctorEmail FROM Doctor WHERE DoctorEmail = :email");
$check_doctor_email->bindParam(':email', $email);
$check_doctor_email->execute();

$doctor_email_result = $check_doctor_email->fetchColumn();

if ($doctor_email_result) {
    //Return true with role "doctor" if email is found in the doctor records
    echo json_encode(array("success" => true, "role" => "doctor"));
} else {
    //Check if email is in receptionist records
    $check_receptionist_email = $local_db->prepare("SELECT ReceptionistEmail FROM Receptionist WHERE ReceptionistEmail = :email");
    $check_receptionist_email->bindParam(':email', $email);
    $check_receptionist_email->execute();

    $receptionist_email_result = $check_receptionist_email->fetchColumn();

    if ($receptionist_email_result) {
        //Return true with role "receptionist" if email is found in the receptionist records
        echo json_encode(array("success" => true, "role" => "receptionist"));
    } else {
        //Return false if email is not found in either doctor or receptionist records
        echo json_encode(array("success" => false));
    }
}
?>