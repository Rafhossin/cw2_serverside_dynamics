<!-- ----------------------
------Author: w1820984
--------------------------->

<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];
$role = $_POST['role'];

//Query depending on the role
if ($role == "doctor") {
    $sql = "SELECT DoctorId, DoctorPassword FROM Doctor WHERE DoctorEmail = :email";
} else if ($role == "receptionist") {
    $sql = "SELECT ReceptionistId, ReceptionistPassword FROM Receptionist WHERE ReceptionistEmail = :email";
} else {
    //Invalid role, return an error message
    echo json_encode(array('success' => false, 'message' => 'Invalid role'));
    exit;
}

$stmt = $local_db->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $stored_password = ($role == "doctor") ? $result['DoctorPassword'] : $result['ReceptionistPassword'];
    $id_result = ($role == "doctor") ? $result['DoctorId'] : $result['ReceptionistId'];

    if (password_verify($password, $stored_password)) {
        //Return true with the doctor or receptionist ID
        echo json_encode(array('success' => true, 'id' => $id_result));
    } else {
        //Return false
        echo json_encode(array('success' => false));
    }
} else {
    //Return false
    echo json_encode(array('success' => false));
}
?>
