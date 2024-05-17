<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muuda söögikohta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Muuda söögikohta</h1>

        <?php
        
        $restaurant_id = $_GET['id'];

        
        $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');
        if ($conn->connect_error) {
            die("Ühenduse viga: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM soogikohad WHERE id = $restaurant_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
        <form action="update.php" method="POST" class="mt-3">
            <input type="hidden" name="restaurant_id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Nimi</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['nimi']; ?>">
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Asukoht</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $row['asukoht']; ?>">
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Söögikoha tüüp</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo $row['tyyp']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Salvesta muudatused</button>
            <a href="index.php" class="btn btn-secondary">Tagasi avalehele</a>
        </form>
        <?php
        } else {
            echo "Söögikohta ei leitud.";
        }
        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
