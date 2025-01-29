<?php
require_once __DIR__ . '/../config.php';
require_once DB_PATH . '_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = (int)$_POST['id'];
    $estado = (int)$_POST['estado'];
    
    $stmt = $conn->prepare("UPDATE listado SET estado = ? WHERE id = ?");
    $stmt->bind_param("ii", $estado, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

$conn->close();
?>
