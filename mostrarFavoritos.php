<?php
$servername = "localhost";
$database = "favoritos";
$username = "root";
$password = "barikelo";
$port = 4004;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

$sql = "SELECT * FROM expFavoritos";
$result = $conn->query($sql);

$favorites = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
}

$conn->close();

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($favorites);
?>
