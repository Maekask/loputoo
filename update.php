<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $restaurant_id = $_POST['restaurant_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $type = $_POST['type'];

    // Connect to the database
    $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');
    if ($conn->connect_error) {
        die("Ühenduse viga: " . $conn->connect_error);
    }

    // Update the restaurant details in the database
    $sql = "UPDATE soogikohad SET nimi='$name', asukoht='$location', tyyp='$type' WHERE id=$restaurant_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the main page after updating
        header("Location: index.php");
        exit();
    } else {
        echo "Viga muutmisel: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // If the form is not submitted, redirect to the index page
    header("Location: index.php");
    exit();
}
?>
