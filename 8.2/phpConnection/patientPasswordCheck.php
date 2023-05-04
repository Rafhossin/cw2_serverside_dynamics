<!-- <?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];

// Check if email exists in database
$check_email = $local_db->prepare("SELECT * FROM LocalPatient WHERE PatientEmail = :email");
$check_email->bindParam(':email', $email);
$check_email->execute();

$patient_record = $check_email->fetch(PDO::FETCH_ASSOC);

if ($patient_record) {
    // Verify password
    if (password_verify($password, $patient_record['PatientPassword'])) {
        // Return true with the NHS number
        echo json_encode(array('success' => true, 'nhsNumber' => $patient_record['NHSNumber']));
    } else {
        // Return false
        echo json_encode(array('success' => false));
    }
} else {
    // Return false
    echo json_encode(array('success' => false));
}

?> -->

<?php
header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');
$password = $_POST['password'];
$email = $_POST['emailAddress'];
$role = $_POST['role'];

if ($role == "doctor") {
    $sql = "SELECT DoctorId, DoctorPassword FROM Doctor WHERE DoctorEmail = :email";
} else if ($role == "receptionist") {
    $sql = "SELECT ReceptionistId, ReceptionistPassword FROM Receptionist WHERE ReceptionistEmail = :email";
}

$stmt = $local_db->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stored_password = ($role == "doctor") ? $result['DoctorPassword'] : $result['ReceptionistPassword'];
    $id_result = ($role == "doctor") ? $result['DoctorId'] : $result['ReceptionistId'];

    if (password_verify($password, $stored_password)) {
        // Return true with the doctor or receptionist ID
        echo json_encode(array('success' => true, 'id' => $id_result));
    } else {
        // Return false
        echo json_encode(array('success' => false));
    }
} else {
    // Return false
    echo json_encode(array('success' => false));
}
?>
