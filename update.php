<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $restaurant_id = $_POST['restaurant_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $type = $_POST['type'];

   
    $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');
    if ($conn->connect_error) {
        die("Ühenduse viga: " . $conn->connect_error);
    }


    $sql = "UPDATE soogikohad SET nimi='$name', asukoht='$location', tyyp='$type' WHERE id=$restaurant_id";

    if ($conn->query($sql) === TRUE) {
        
        header("Location: index.php");
        exit();
    } else {
        echo "Viga muutmisel: " . $conn->error;
    }

    
    $conn->close();
} else {
    
    header("Location: index.php");
    exit();
}
?>
