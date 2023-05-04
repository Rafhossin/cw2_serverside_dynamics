<?php
$local_db = new PDO('sqlite:GpSurgery.db');

//http://localhost:8005/insertDrDetails.php

// 1	Raf	Hossin	 rafhossin@gmail.com	password:raf@1525

//2	Domingo	Trimarchi	 domingo@gamil.com	        password:domingo@6667

//3	David	Milan	david@gmail.com	     password:dav@1990

//4	Addy 	Islam	ady@gmail.com	   password:ady@1987

//5	Romel	Ahmed	romel@gmail.com	     password:romel@13556

//6	Martin 	Roach	martin@yahoo.co.uk	         password:martin@2234


//Inserting the doctor details from the above
$doctorFirstName = "Martin";
$doctorSurName = "Roach";
$doctorEmail = "martin@yahoo.co.uk";
$doctorPassword = "martin@2234";

// Hash the password
$hashed_password = password_hash($doctorPassword, PASSWORD_DEFAULT);

// Prepare the SQL statement
$insertDrDetails = $local_db->prepare("INSERT INTO Doctor (DoctorFirstName, DoctorSurName, DoctorEmail, DoctorPassword) VALUES (?, ?, ?, ?)");

// Bind the parameters to the prepared statement
$insertDrDetails->bindParam(1, $doctorFirstName);
$insertDrDetails->bindParam(2, $doctorSurName);
$insertDrDetails->bindParam(3, $doctorEmail);
$insertDrDetails->bindParam(4, $hashed_password);

// Execute the prepared statement
if ($insertDrDetails->execute()) {
    echo "New doctor added successfully";
} else {
    echo "Error: " .implode(" - ", $insertDrDetails->errorInfo());
}

// Prepare the SQL statement
$selectAllDoctors = $local_db->prepare("SELECT * FROM Doctor");

// Execute the prepared statement
$selectAllDoctors->execute();

// Fetch all the rows as an associative array
$doctors = $selectAllDoctors->fetchAll(PDO::FETCH_ASSOC);

// Display all doctors
foreach ($doctors as $doctor) {
   
    echo "First Name: " . $doctor['DoctorFirstName'] . "<br>";
    echo "Surname: " . $doctor['DoctorSurName'] . "<br>";
    echo "Email: " . $doctor['DoctorEmail'] . "<br><br>";
}



// Close the prepared statement
$insertDrDetails = null;

// Close the prepared statement
$selectAllDoctors = null;
?>
