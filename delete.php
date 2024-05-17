<?php
// Check if the restaurant ID is provided in the URL
if (isset($_GET['id'])) {
    // Get the restaurant ID from the URL parameter
    $restaurant_id = $_GET['id'];

    // Connect to the database
    $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

    // Check the database connection
    if ($conn->connect_error) {
        die("Ühenduse viga: " . $conn->connect_error);
    }

    // SQL to delete a record
    $sql = "DELETE FROM soogikohad WHERE id=$restaurant_id";

    // Execute the delete query
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the main page after deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Viga kustutamisel: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Puudub söögikoha ID.";
}
?>
