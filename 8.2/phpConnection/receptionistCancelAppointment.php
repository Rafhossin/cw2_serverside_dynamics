<?php
//----------------------
////Author: w1822557
//----------------------

header("Access-Control-Allow-Origin: *");
$local_db = new PDO('sqlite:GpSurgery.db');

$appointmentNo = $_POST['AppointmentNo'];

// Check if the appointment exists
$check_appointment = $local_db->prepare("SELECT * FROM GPAppointment WHERE AppointmentNo = :appointmentNo");
$check_appointment->bindParam(':appointmentNo', $appointmentNo);
$check_appointment->execute();

$appointment = $check_appointment->fetch(PDO::FETCH_ASSOC);

if ($appointment) {
    // Delete the appointment
    $delete_appointment = $local_db->prepare("DELETE FROM GPAppointment WHERE AppointmentNo = :appointmentNo");
    $delete_appointment->bindParam(':appointmentNo', $appointmentNo);
    $delete_appointment->execute();

    // Return success response
    echo json_encode(array('success' => true));
} else {
    // Return error response
    echo json_encode(array('success' => false, 'message' => 'Appointment not found.'));
}
?>