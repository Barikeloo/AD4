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

// Recibir datos del cliente (título, imagen y descripción)
$title = $_POST['title'];
$imageUrl = $_POST['imageUrl'];
$description = $_POST['description'];

// Verificar si ya existe una entrada con la misma información
$checkDuplicateSql = "SELECT * FROM expFavoritos WHERE title = ? AND imageUrl = ? AND description = ?";
$checkDuplicateStmt = $conn->prepare($checkDuplicateSql);
$checkDuplicateStmt->bind_param("sss", $title, $imageUrl, $description);
$checkDuplicateStmt->execute();
$checkDuplicateResult = $checkDuplicateStmt->get_result();

if ($checkDuplicateResult->num_rows > 0) {
    // Ya existe una entrada con la misma información
    $response['success'] = false;
    $response['message'] = "Esta explicación ya está en tus favoritos.";
} else {
    // No existe una entrada duplicada, proceder con la inserción
    $insertSql = "INSERT INTO expFavoritos (title, imageUrl, description) VALUES (?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("sss", $title, $imageUrl, $description);

    if ($insertStmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Datos añadidos a la tabla expFavoritos correctamente";
    } else {
        $response['success'] = false;
        $response['message'] = "Error al añadir datos a la tabla expFavoritos: " . $insertStmt->error;
    }

    $insertStmt->close();
}

$checkDuplicateStmt->close();
$conn->close();

// Enviar la respuesta como JSON al cliente
header('Content-Type: application/json');
echo json_encode($response);
?>
