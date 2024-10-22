<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "detecciones_database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta a la tabla "detecciones" para obtener las fechas
$sql = "SELECT IdDeteccion, DATE_FORMAT(Fecha,'%d/%m/%Y %H:%i:%s') AS FechaFormato FROM detecciones";
$result = $conn->query($sql);

// Arreglo de fechas
$dates = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = array('id' => $row['IdDeteccion'], 'fecha' => $row['FechaFormato']);
    }
}

echo json_encode($dates);

$conn->close();

?>
