<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

//header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//header('Content-Type: application/json');




 // Connect to database
 $local_db = new PDO('sqlite:GpSurgery.db');

// function displayLocalPatients($db) {
//     // Fetch all rows from the LocalPatient table
//     $fetch_all_row = $db->prepare("SELECT * FROM LocalPatient");
//     $fetch_all_row->execute();
//     $all_rows = $fetch_all_row->fetchAll(PDO::FETCH_ASSOC);

//     echo "<h2>Local Patients:</h2>";
    
//     if (empty($all_rows)) {
//         echo "No records found in the LocalPatient table.<br>";
//     } else {
//         foreach ($all_rows as $i => $row) {
//             echo "<p>";
//             echo "Row " . ($i+1) . ":<br>";
//             // Display the row data here
//             echo "NHSNumber: " . $row['NHSNumber'] . "<br>";
//             echo "Forename: " . $row['Forename'] . "<br>";
//             echo "Surname: " . $row['Surname'] . "<br>";
//             echo "PersonDOB: " . $row['PersonDOB'] . "<br>";
//             echo "GenderCode: " . $row['GenderCode'] . "<br>";
//             echo "Postcode: " . $row['Postcode'] . "<br>";
//             echo "PatientEmail: " . $row['PatientEmail'] . "<br>";
//             echo "PatientPassword: " . $row['PatientPassword'] . "<br>";
//             echo "</p>";
//         }
//     }
// }

// function displayReceptionists($db) {
   
//     // Fetch all rows from the Receptionist table
// $fetch_all_row = $db->prepare("SELECT * FROM Receptionist");
// $fetch_all_row->execute();
// $all_rows = $fetch_all_row->fetchAll(PDO::FETCH_ASSOC);

// echo "<h2>Receptionist:</h2>";

// if (empty($all_rows)) {
//     echo "No records found in the Receptionist table.";
// } else {
//     // Loop through each row and display the data
// foreach ($all_rows as $i => $row) {
//     echo "<p>";
//     echo "Row " . ($i+1) . ":<br>";
//     echo "ReceptionistId: " . $row['ReceptionistId'] . "<br>";
//     echo "ReceptionistFirstName: " . $row['ReceptionistFirstName'] . "<br>";
//     echo "ReceptionistSurName: " . $row['ReceptionistSurName'] . "<br>";
//     echo "ReceptionistEmail: " . $row['ReceptionistEmail'] . "<br>";
//     echo "ReceptionistPassword: " . $row['ReceptionistPassword'] . "<br>";
//     echo "<br>";
//     echo "</p>";
//     }
// }
// }

// function displayGPAppointments($db) {
//     // Fetch all rows from the GPAppointment table
//  $fetch_all_row = $db->prepare("SELECT * FROM GPAppointment");
//  $fetch_all_row->execute();
//  $all_rows = $fetch_all_row->fetchAll(PDO::FETCH_ASSOC);

//  echo "<h2>GPAppointment:</h2>";

//  if (empty($all_rows)) {
//      echo "No records found in the GPAppointment table.<br>";
//  } else {
//      // Loop through each row and display the data
//      foreach ($all_rows as $i => $row) {
//         echo "<p>";
//          echo "Row " . ($i+1) . ":<br>";
//          echo "AppointmentNo: " . $row['AppointmentNo'] . "<br>";
//          echo "AppointmentDate: " . $row['AppointmentDate'] . "<br>";
//          echo "AppointmentTime: " . $row['AppointmentTime'] . "<br>";
//          echo "NHSNumber: " . $row['NHSNumber'] . "<br>";
//          echo "DoctorId: " . $row['DoctorId'] . "<br>";
//          echo "<br>";
//          echo "</p>";
//      }
//  }


// }

// function displayMedicalRecords($db) {
//     // Fetch all rows from the MedicalRecord table
// $fetch_all_row = $db->prepare("SELECT * FROM MedicalRecord");
// $fetch_all_row->execute();
// $all_rows = $fetch_all_row->fetchAll(PDO::FETCH_ASSOC);

// echo "<h2>MedicalRecord :</h2>";

// if (empty($all_rows)) {
//     echo "No records found in the MedicalRecord table.<br>";
// } else {
//     // Loop through each row and display the data
//     foreach ($all_rows as $i => $row) {
//         echo "<p>";
//         echo "Row " . ($i+1) . ":<br>";
//         echo "NHSNumber: " . $row['NHSNumber'] . "<br>";
//         echo "DoseNo: " . $row['DoseNo'] . "<br>";
//         echo "DoctorId: " . $row['DoctorId'] . "<br>";
//         echo "VaccinationDate: " . $row['VaccinationDate'] . "<br>";
//         echo "VaccineManufacturer: " . $row['VaccineManufacturer'] . "<br>";
//         echo "DiseaseTargeted: " . $row['DiseaseTargeted'] . "<br>";
//         echo "VaccineType: " . $row['VaccineType'] . "<br>";
//         echo "Product: " . $row['Product'] . "<br>";
//         echo "VaccineBatchNumber: " . $row['VaccineBatchNumber'] . "<br>";
//         echo "CountryOfVaccination: " . $row['CountryOfVaccination'] . "<br>";
//         echo "Authority: " . $row['Authority'] . "<br>";
//         echo "Site: " . $row['Site'] . "<br>";
//         echo "TotalSeriesOfDoses: " . $row['TotalSeriesOfDoses'] . "<br>";
//         echo "DisplayName: " . $row['DisplayName'] . "<br>";
//         echo "SnomedCode: " . $row['SnomedCode'] . "<br>";
//         echo "DateEntered: " . $row['DateEntered'] . "<br>";
//         echo "ProcedureCode: " . $row['ProcedureCode'] . "<br>";
//         echo "Booster: " . ($row['Booster'] ? 'Yes' : 'No') . "<br>";
//         echo "<br>";
//         echo "</p>";
//     }
// }

// }

// try {
//     displayLocalPatients($local_db);
//     displayReceptionists($local_db);
//     displayGPAppointments($local_db);
//     displayMedicalRecords($local_db);
// } catch (PDOException $e) {
//     echo "Error: " . $e->getMessage();
// }






// header("Access-Control-Allow-Origin: *");

// // Connect to central database
// $local_db = new PDO('sqlite:GpSurgery.db');

// // Get the email from the AJAX post request

 $email = $_POST['emailAddress'];
    // do something with the email variable
// //$email = "haf@gmail.com";
// // Validate email format
// // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
// //     // Return error message as a JSON object to the registration page
// //     header('Content-Type: application/json');
// //     echo json_encode(array('error' => 'Invalid email format'));
// //     exit;
// // }

// // Prepare the SELECT statement to check if the table exists
// // $check_table = $local_db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=:table_name");

// // Bind the table_name parameter
// // $table_name = 'MedicalRecord';
// // $check_table->bindParam(':table_name', $table_name);

// // // Execute the SELECT statement
// // $check_table->execute();

// // // Fetch the result
// // $result = $check_table->fetch();

// // if ($result) {
// //     echo "Table '$table_name' exists.";
// // } else {
// //     echo "Table '$table_name' does not exist.";
// // }

// // Debugging: Fetch all rows from the LocalPatient table
// $fetch_all_row = $local_db->prepare("SELECT * FROM LocalPatient");
// $fetch_all_row->execute();
// $all_row = $fetch_all_row->fetchAll(PDO::FETCH_ASSOC);

// echo "<pre>";
// print_r($all_row);
// echo "</pre>";

// //$local_db = new PDO('sqlite:vaccines.db');

// // Debugging: Fetch all rows from the vaccines table
// // $fetch_all_rows = $local_db->prepare("SELECT * FROM vaccines");
// // $fetch_all_rows->execute();
// // $all_rows = $fetch_all_rows->fetchAll(PDO::FETCH_ASSOC);

// // echo "<pre>";
// // print_r($all_rows);
// // echo "</pre>";



 // Get the email from the AJAX post request
//$email = $_POST["email"];

// Get the JSON data from the request body
// $json_data = json_decode(file_get_contents("php://input"), true);


// if ($json_data === null || !array_key_exists('email', $json_data)) {
//     echo json_encode(["error" => "Invalid data received", "received_data" => file_get_contents("php://input")]);
//     exit();
// }



// $email = $json_data['email'];
//$email = "haf@gmail.com";
  // Validate email format
//  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
//  {     // Return error message as a JSON object to the registration page
//      header('Content-Type: application/json');
//      echo json_encode(array('error' => 'Invalid email format'));
//      exit;
//  }

 //Check if email is in database
 $check_email = $local_db->prepare("SELECT * FROM LocalPatient WHERE PatientEmail = :email");
 $check_email->bindParam(':email', $email);
 $check_email->execute();
// Fetch the result set as an associative array
 //$result = $check_email->fetch(PDO::FETCH_ASSOC);

 $email_found = false;
 $email_result = "";

 while ($row = $check_email->fetch(PDO::FETCH_ASSOC)) {
     if ($row['PatientEmail'] === $email) {
         $email_found = true;
         $email_result = $row['PatientEmail'];
        break;
     }
 }

 

 if( $email_found) {
    // Return true as a JSON object to the registration page
     header('Content-Type: application/json');
     echo json_encode(array('exists' => true, 'email' => $email_result));
     //echo json_encode($result);
 } else {
     // Return false as a JSON object to the registration page
     header('Content-Type: application/json');
    echo json_encode(array('exists' => false));
    // echo "no car";
 }
?>




