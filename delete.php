<?php

if (isset($_GET['id'])) {
   
    $restaurant_id = $_GET['id'];

   
    $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

    
    if ($conn->connect_error) {
        die("Ühenduse viga: " . $conn->connect_error);
    }

    
    $sql = "DELETE FROM soogikohad WHERE id=$restaurant_id";

    
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the main page after deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Viga kustutamisel: " . $conn->error;
    }

    
    $conn->close();
} else {
    echo "Puudub söögikoha ID.";
}
?>
