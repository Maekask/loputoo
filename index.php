<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Söögikohad</title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Söögikohad</h1>

       
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Otsi nime järgi" aria-label="Search" name="search">
                <button class="btn btn-outline-secondary" type="submit">Otsi</button>
            </div>
        </form>

        <?php
        
        $conn = new mysqli('localhost', 'mart', 'parool', 'loputoo');

        
        if ($conn->connect_error) {
            die("Ühenduse viga: " . $conn->connect_error);
        }

        
        $results_per_page = 10;
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $start_from = ($page - 1) * $results_per_page;

        
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $sortDirection = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';
        $validColumns = ['nimi', 'asukoht', 'keskmine_hinne', 'hinnatud'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'id';
        }
        $sortIcon = $sortDirection === 'ASC' ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="20" height="20" data-slot="icon"><path fill-rule="evenodd" d="M6.97 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06L8.25 4.81V16.5a.75.75 0 0 1-1.5 0V4.81L3.53 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5Zm9.53 4.28a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V7.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="25" height="25" data-slot="icon"><path fill-rule="evenodd" d="M16.03 21.53a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.22 3.22V3.75a.75.75 0 0 1 1.5 0v15.44l3.22-3.22a.75.75 0 0 1 1.06 1.06l-4.5 4.5Zm-9.53-4.28a.75.75 0 0 1-.75-.75V5.06l-3.22 3.22a.75.75 0 1 1-1.06-1.06l4.5-4.5a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 1 1-1.06 1.06L6.28 5.06v11.69a.75.75 0 0 1-.75.75Z" clip-rule="evenodd"/></svg>';

        
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            
            $sql = "SELECT * FROM soogikohad WHERE nimi LIKE '%$search%' ORDER BY $sortColumn $sortDirection LIMIT $start_from, $results_per_page";
        } else {
            
            $sql = "SELECT * FROM soogikohad ORDER BY $sortColumn $sortDirection LIMIT $start_from, $results_per_page";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            echo "<table class='table table-bordered mt-3'>
                    <thead>
                        <tr>
                            <th><a href='{$_SERVER['PHP_SELF']}?sort=nimi&dir=" . ($sortColumn === 'nimi' && $sortDirection === 'ASC' ? 'DESC' : 'ASC') . "'>Nimi $sortIcon</a></th>
                            <th><a href='{$_SERVER['PHP_SELF']}?sort=asukoht&dir=" . ($sortColumn === 'asukoht' && $sortDirection === 'ASC' ? 'DESC' : 'ASC') . "'>Asukoht $sortIcon</a></th>
                            <th><a href='{$_SERVER['PHP_SELF']}?sort=keskmine_hinne&dir=" . ($sortColumn === 'keskmine_hinne' && $sortDirection === 'ASC' ? 'DESC' : 'ASC') . "'>Keskmine hinne $sortIcon</a></th>
                            <th><a href='{$_SERVER['PHP_SELF']}?sort=hinnatud_kordi&dir=" . ($sortColumn === 'hinnatud_kordi' && $sortDirection === 'ASC' ? 'DESC' : 'ASC') . "'>Hinnatud kordi $sortIcon</a></th>
                        </tr>
                    </thead>
                    <tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td><a href='hinda.php?id=" . $row["id"] . "'>" . $row["nimi"] . "</a></td>
                        <td>" . $row["asukoht"] . "</td>
                        <td>" . $row["keskmine_hinne"] . "</td>
                        <td>" . $row["hinnatud"] . "</td>
                    </tr>";
            }
            echo "</tbody>
                </table>";

            
            $sql = "SELECT COUNT(id) AS total FROM soogikohad";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $total_pages = ceil($row["total"] / $results_per_page);

            echo "<nav aria-label='Page navigation'>
                    <ul class='pagination'>";
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=1'>Esimene</a></li>";
                echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=".($page - 1)."'>Eelmine</a></li>";
            }
            if ($page < $total_pages) {
                echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=".($page + 1)."'>Järgmine</a></li>";
                echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=".$total_pages."'>Viimane</a></li>";
            }
            echo "</ul>
                </nav>";
        } else {
            echo "Söögikohti ei leitud.";
        }
        $conn->close();
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
