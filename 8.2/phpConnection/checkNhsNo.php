<?php
//----------------------
////Author: w1820984
//----------------------

header("Access-Control-Allow-Origin: *");

$central_db = new PDO('sqlite:vaccines.db');

$nhs_number = $_POST['nhsNumber'];

//Check if NHS number exists in central database
$check_nhs_number = $central_db->prepare('SELECT NHSNumber FROM patients WHERE NHSNumber = :nhs_number');
$check_nhs_number->bindParam(':nhs_number', $nhs_number);
$check_nhs_number->execute();
$nhs_number_result = $check_nhs_number->fetchColumn();

//Check if NHS number exists in GpSurgery database
$gp_db = new PDO('sqlite:GpSurgery.db');
$check_gp_number = $gp_db->prepare('SELECT NHSNumber FROM LocalPatient WHERE NHSNumber = :nhs_number');
$check_gp_number->bindParam(':nhs_number', $nhs_number);
$check_gp_number->execute();
$gp_number_result = $check_gp_number->fetchColumn();

if ($gp_number_result) {
    echo "This NHS number is already registered to our local database";
} else if ($nhs_number_result) {
    echo $nhs_number_result;
} else {
    echo "NHSNumber does not exist or invalid format";
}
?>