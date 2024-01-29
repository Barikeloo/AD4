<?php
$servername = "localhost";
$database = "favoritos";
$username = "root";
$password = "barikelo";
$port = 4004;

$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Recibir el título del cliente
$title = $_POST['title'];

// Preparar la consulta SQL para eliminar la entrada de favoritos
$deleteSql = "DELETE FROM expFavoritos WHERE title = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("s", $title);

// Ejecutar la sentencia
$response = array();
if ($deleteStmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Entrada eliminada de favoritos correctamente";
} else {
    $response['success'] = false;
    $response['message'] = "Error al eliminar entrada de favoritos: " . $deleteStmt->error;
}

// Cerrar la conexión a la base de datos
$deleteStmt->close();
$conn->close();

// Enviar la respuesta como JSON al cliente
header('Content-Type: application/json');
echo json_encode($response);
?>
