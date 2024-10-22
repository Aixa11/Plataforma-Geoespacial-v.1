<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "detecciones_database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtengo la fecha seleccionada en el checkbox
$id = $_GET["id"];

$sql= "SELECT ST_AsGeoJSON(manchas.Mancha) AS Mancha FROM manchas 
        WHERE idDeteccion=$id";
        
$result = $conn->query($sql);

// Arreglo de polígonos en formato GeoJSON
$features = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //$features[] = $row["Mancha"];
        $features[] = json_decode($row['Mancha']);
    }
}

// Devuelve el arreglo
echo json_encode($features);

$conn->close();

?>
