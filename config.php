<?php
$kasutaja = "mart";
$dbserver = "localhost";
$andmebaas = "loputoo";
$pw = "parool";
$username = 'admin';
$password = 'password';

$yhendus = mysqli_connect($dbserver, $kasutaja, $pw, $andmebaas);

if(!$yhendus) {
    echo "Jama majas";
    die("Sa jälle ebaõnnestusid!");
}

?>