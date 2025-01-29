<?php
require_once __DIR__ . '/../config.php';
require_once DB_PATH . '_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['descripcion'])) {
    $descripcion = trim($_POST['descripcion']);
    
    $stmt = $conn->prepare("INSERT INTO listado (descripcion) VALUES (?)");
    $stmt->bind_param("s", $descripcion);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL);
    } else {
        echo "Error al crear la tarea: " . $conn->error;
    }
    
    $stmt->close();
} else {
    header("Location: " . BASE_URL);
}

$conn->close();
?>
