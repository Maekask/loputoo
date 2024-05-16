<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
    session_start();

    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }

    
    $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

    
    if ($conn->connect_error) {
        die("Ühenduse viga: " . $conn->connect_error);
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_add'])) {
        $nimi = $_POST['nimi'];
        $aadress = $_POST['aadress'];
        $tyyp = $_POST['tyyp']; 

       
        $sql = "INSERT INTO soogikohad (nimi, asukoht, tyyp) VALUES ('$nimi', '$aadress', '$tyyp')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Söögikoht on edukalt lisatud.";
        } else {
            echo "Viga: " . $conn->error;
        }
    }

    // Kui kasutaja soovib söögikohta muuta
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
        $id = $_POST['id'];
        $nimi = $_POST['nimi'];
        $aadress = $_POST['aadress'];
       
        $sql = "UPDATE soogikohad SET nimi='$nimi', asukoht='$aadress' WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            echo "Söögikoht on edukalt muudetud.";
        } else {
            echo "Viga: " . $conn->error;
        }
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_delete'])) {
        $id = $_POST['id'];

        
        $sql = "DELETE FROM soogikohad WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            echo "Söögikoht on edukalt kustutatud.";
        } else {
            echo "Viga: " . $conn->error;
        }
    }

    
    function displayRestaurants($conn) {
        $sql = "SELECT id, nimi, asukoht FROM soogikohad";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Söögikohad:</h2>";
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Nimi</th>';
            echo '<th scope="col">Aadress</th>';
            echo '<th scope="col">Tegevused</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['nimi'] . '</td>';
                echo '<td>' . $row['asukoht'] . '</td>';
                echo '<td><a href="dashboard.php?action=edit&id=' . $row['id'] . '">Muuda</a> | <form method="post" style="display:inline"><input type="hidden" name="id" value="' . $row['id'] . '"><input type="submit" name="submit_delete" value="Kustuta"></form></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo "Söögikohti ei leitud.";
        }
    }

    
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT id, nimi, asukoht FROM soogikohad WHERE id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $edit_id = $row['id'];
            $edit_name = $row['nimi'];
            $edit_address = $row['asukoht'];
        ?>
        <h2>Muuda söögikohta:</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <div class="mb-3">
                <label for="nimi">Nimi:</label>
                <input type="text" id="nimi" name="nimi" class="form-control" value="<?php echo $edit_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="aadress">Aadress:</label>
                <input type="text" id="aadress" name="aadress" class="form-control" value="<?php echo $edit_address; ?>" required>
            </div>
            <button type="submit" name="submit_edit" class="btn btn-primary">Salvesta muudatused</button>
        </form>
        <?php
        }
    } else {
   
        displayRestaurants($conn);
    ?>
    <h2>Lisa uus söögikoht:</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nimi">Nimi:</label>
            <input type="text" id="nimi" name="nimi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="aadress">Aadress:</label>
            <input type="text" id="aadress" name="aadress" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tyyp">Tüüp:</label>
            <input type="text" id="tyyp" name="tyyp" class="form-control" required>
        </div>
        <button type="submit" name="submit_add" class="btn btn-primary">Lisa söögikoht</button>
    </form>
    <?php
    }

    
    $conn->close();
    ?>
    <a href="index.php" class="btn btn-secondary mt-3">Tagasi</a> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
