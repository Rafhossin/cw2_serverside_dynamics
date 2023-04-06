<?php
header("Access-Control-Allow-Origin: *");
// Connect to the database using PDO
$db = new PDO('sqlite:car.db');

// Get the car registration number from the AJAX request
$carReg = $_POST['plateNo'];

// Query the database for the car tax information
$query = "SELECT tax,colour FROM cars WHERE carReg = :carReg";
$stmt = $db->prepare($query);
$stmt->bindParam(':carReg', $carReg);
$stmt->execute();

// Fetch the result set as an associative array
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the query returned any results
if ($result !== false) {
    // Return the tax information as a JSON object
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    // Return an error message if no results were found
    echo "no car";
   // echo "No results found for car registration number: ".$carReg;
}

// Close the database connection
$db = null;

?>

