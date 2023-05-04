<?php
  header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];
$role = $_POST['role'];

if ($role == "doctor") {
  $check_email_password = $local_db->prepare("SELECT DoctorId FROM Doctor WHERE DoctorEmail = :email AND DoctorPassword = :password");
} else if ($role == "receptionist") {
  $check_email_password = $local_db->prepare("SELECT ReceptionistId FROM Receptionist WHERE ReceptionistEmail = :email AND ReceptionistPassword = :password");
}

$check_email_password->bindParam(':email', $email);
$check_email_password->bindParam(':password', $password);
$check_email_password->execute();

$id_result = $check_email_password->fetchColumn();

if ($id_result) {
  // Return true with the doctor or receptionist ID
  echo json_encode(array('success' => true, 'id' => $id_result));
} else {
  // Return false
  echo json_encode(array('success' => false));
}
?> 