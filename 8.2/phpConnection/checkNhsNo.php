<?php
header("Access-Control-Allow-Origin: *");

// Connect to central database
$central_db = new PDO('sqlite:vaccines.db');

// Get the nhs number from the AJAX post request
$nhs_number = $_POST['nhsNumber'];

// Check if NHS number exists in central database
$check_nhs_number = $central_db->prepare('SELECT NHSNumber FROM patients WHERE NHSNumber = :nhs_number');
$check_nhs_number->bindParam(':nhs_number', $nhs_number);
$check_nhs_number->execute();
$nhs_number_result = $check_nhs_number->fetchColumn();

// Check if NHS number exists in GpSurgery database
$gp_db = new PDO('sqlite:GpSurgery.db');
$check_gp_number = $gp_db->prepare('SELECT NHSNumber FROM LocalPatient WHERE NHSNumber = :nhs_number');
$check_gp_number->bindParam(':nhs_number', $nhs_number);
$check_gp_number->execute();
$gp_number_result = $check_gp_number->fetchColumn();

if ($gp_number_result) {
    // NHS number is already registered to our local database
    echo "This NHS number is already registered to our local database";
} else if ($nhs_number_result) {
    // Return NHS number as a string to the registration page
    echo $nhs_number_result;
} else {
    // NHSNumber does not exist in central database
    echo "NHSNumber does not exist in central database";
}
?>