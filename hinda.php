<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hinnang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .rating {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
        }
        .rating > input {
            display: none;
        }
        .rating > label {
            display: inline-block;
            margin: 0 2px;
            font-size: 30px;
            cursor: pointer;
            color: #ccc;
        }
        .rating > input:checked ~ label {
            color: orange;
        }
        .rating > input:hover ~ label,
        .rating > label:hover,
        .rating > label:hover ~ label {
            color: orange;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        
        $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

        if ($conn->connect_error) {
            die("Ühenduse viga: " . $conn->connect_error);
        }

        if(isset($_GET['id'])) {
            $soogikoht_id = $_GET['id'];
            $sql = "SELECT nimi FROM soogikohad WHERE id = $soogikoht_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nimi = $row['nimi'];
                echo '<h1 class="mt-5">' . $nimi . '</h1>';
            } else {
                echo '<h1 class="mt-5">Sisesta hinnang</h1>';
            }
        } else {
            echo '<h1 class="mt-5">Sisesta hinnang</h1>';
        }

        $conn->close();
        ?>
        
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nimi = $_POST['nimi'];
            $hinne = $_POST['hinne'];
            $kommentaar = $_POST['kommentaar'];

            
            $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

            
            if ($conn->connect_error) {
                die("Ühenduse viga: " . $conn->connect_error);
            }

            
            $soogi_id = isset($_GET['id']) ? $_GET['id'] : '';
            $sql_fetch_nimi_id = "SELECT id FROM soogikohad WHERE id = $soogi_id";
            $result_nimi_id = $conn->query($sql_fetch_nimi_id);

            if ($result_nimi_id->num_rows > 0) {
                $row_nimi_id = $result_nimi_id->fetch_assoc();
                $nimi_id = $row_nimi_id['id'];
            } else {
                
                echo "Error: Söögikohta ei leitud.";
                exit;
            }

            
            $sql = "INSERT INTO hinnangud (soogi_id, nimi_id, kasutaja, hinne, kommentaar) VALUES ('$soogi_id', '$nimi_id', '$nimi', '$hinne', '$kommentaar')";

            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-success" role="alert">Hinnang salvestatud edukalt.</div>';
                
             
                header("Location: index.php");
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">Viga: ' . $sql . '<br>' . $conn->error . '</div>';
            }

            $conn->close();
        }
        ?>
        

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            <div class="mb-3">
                <label for="nimi" class="form-label">Nimi:</label>
                <input type="text" name="nimi" id="nimi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hinne:</label><br>
                <div class="rating">
                    <input type="radio" id="star10" name="hinne" value="10" required><label for="star10">&#9733;</label>
                    <input type="radio" id="star9" name="hinne" value="9"><label for="star9">&#9733;</label>
                    <input type="radio" id="star8" name="hinne" value="8"><label for="star8">&#9733;</label>
                    <input type="radio" id="star7" name="hinne" value="7"><label for="star7">&#9733;</label>
                    <input type="radio" id="star6" name="hinne" value="6"><label for="star6">&#9733;</label>
                    <input type="radio" id="star5" name="hinne" value="5"><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="hinne" value="4"><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="hinne" value="3"><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="hinne" value="2"><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="hinne" value="1"><label for="star1">&#9733;</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="kommentaar" class="form-label">Kommentaar:</label>
                <textarea name="kommentaar" id="kommentaar" rows="5" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Saada</button>
            <a href="index.php" class="btn btn-secondary">Tagasi</a>
        </form>


        <?php
   
        $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

  
        if ($conn->connect_error) {
            die("Ühenduse viga: " . $conn->connect_error);
        }

        
        $soogi_id = isset($_GET['id']) ? $_GET['id'] : '';
        $sql_reviews = "SELECT kasutaja, hinne, kommentaar FROM hinnangud WHERE soogi_id = $soogi_id";
        $result_reviews = $conn->query($sql_reviews);

        if ($result_reviews->num_rows > 0) {
            echo '<h2 class="mt-5">Teiste hinnangud:</h2>';
            echo '<ul>';
            while ($row_review = $result_reviews->fetch_assoc()) {
                echo '<li>';
                echo '<strong>' . $row_review['kasutaja'] . ': </strong>';
                echo 'Hinne: ' . $row_review['hinne'] . ', Kommentaar: ' . $row_review['kommentaar'];
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p class="mt-3">Selle söögikoha kohta pole veel hinnanguid antud.</p>';
        }

        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
