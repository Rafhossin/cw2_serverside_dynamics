<?php
//----------------------
////Author: w1820984
//----------------------

header("Access-Control-Allow-Origin: *");

$local_db = new PDO('sqlite:GpSurgery.db');

$email = $_POST['emailAddress'];

//Check if email exists in local database
$check_email = $local_db->prepare('SELECT * FROM LocalPatient WHERE PatientEmail = :email');
$check_email->bindParam(':email', $email);
$check_email->execute();
$existing_email = $check_email->fetch(PDO::FETCH_ASSOC);

//Check if email already exists in local database
if ($existing_email) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false));
}